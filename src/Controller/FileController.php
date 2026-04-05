<?php

declare(strict_types=1);

namespace App\Controller;

use App\Builder\FiletypeBuilder;
use App\Entity\File;
use App\Entity\Radioplay;
use App\Form\FileUploadFormType;
use App\Model\FileUploadModel;
use App\StorageProvider\GoogleCloudStorageProvider;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;

class FileController extends AbstractController
{
    #[Route('/files/{uuid}', name: 'file_load')]
    public function loadFile(
//        #[MapEntity(mapping: ['uuid' => 'uuid'])] Media $media
        #[ValueResolver('uuid')] File $file,
        #[Autowire(env: 'VITE_CLOUD_STORAGE_SERVER')]
        string                        $cloudStorageProviderUrl,
    ): Response
    {
        if ($file->getStorage() === 'terabox') {
            $client = new Client();
            $response = $client->get($cloudStorageProviderUrl . '/api/download/' . $file->getPath(), [
                'stream' => true,
                'query' => [
                    'storage' => 'terabox',
                ]
            ]);

//            $data = $response->getBody()->getContents();
            $body = $response->getBody();

            $response = new StreamedResponse(function () use ($body) {
                while (!$body->eof()) {
                    $chunk = $body->read(32192);
                    echo $chunk; // process or save
                }
            });

            $response->headers->set('Content-Type', $file->getType());
            $response->headers->set('Content-Length', (string)$file->getSize());
//        $response->headers->set('Content-Disposition', 'inline; filename="' . basename($path) . '"');
//            $response->setMaxAge(3600);

            return $response;
        }


        if ($file->getStorage() == 'h3') {
            $url = $file->getPath();
            $path = $url;
        }

        $response = new StreamedResponse(static function () use ($path) {
            $options = [
                'http' => [
                    'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n" .
                        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n"
                ]
            ];
            $context = stream_context_create($options);

//            $content = file_get_contents($path, false, $context);

            $handle = fopen($path, 'rb');

            while (!feof($handle)) {
                echo fread($handle, 32192);   // stream in chunks
                flush();
            }

            //fclose($handle);

        });

        // Headers
        $response->headers->set('Content-Type', $file->getType());
        $response->headers->set('Content-Length', (string)$file->getSize());
//        $response->headers->set('Content-Disposition', 'inline; filename="' . basename($path) . '"');
        $response->setMaxAge(3600);

        return $response;
    }

    /**
     * @throws Exception
     */
    #[Route('/api/file_upload', name: 'file_upload', methods: ['POST', 'OPTIONS'])]
    public function uploadFile(
        Request                $request,
        EntityManagerInterface $entityManager,
        #[Autowire(env: 'VITE_CLOUD_STORAGE_SERVER')]
        string                 $cloudStorageProviderUrl,
    ): Response
    {
        $this->devEnvironment();

        if ($request->getMethod() === Request::METHOD_OPTIONS) {
            return new Response();
        }

        $fileUploadModel = new FileUploadModel();
        $form = $this->createForm(FileUploadFormType::class, $fileUploadModel);

        $form->handleRequest($request);

        $callbackData = [];
        $alreadyExists = false;

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var FileUploadModel $uploadModel */
            $uploadModel = $form->getData();

            $uploadedFile = $uploadModel->getFile();
            $path = $uploadModel->getPath();
            $storage = $uploadModel->getStorage();
            $discriminator = $uploadModel->getDiscr();
            $desiredFilename = $uploadModel->getFilename();

            if (null === $uploadedFile) {
                return $this->json(['message' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
            }

            if (!$uploadedFile->isValid()) {
                return $this->json(['message' => 'File upload error'], Response::HTTP_BAD_REQUEST);
            }

            $localUploadDir = $this->getParameter('kernel.project_dir') . '/uploads/' . $path;
            $filename = $this->sanitizeFilename($desiredFilename);
            $realUUID = Uuid::v4();
            $url = $this->generateUrl('file_load', ['uuid' => $realUUID], UrlGeneratorInterface::ABSOLUTE_URL);

            $fileBuilder = FiletypeBuilder::create()
                ->withDiscriminator($discriminator)
                ->withUploadedFile($uploadedFile)
                ->withUUID($realUUID)
                ->withUrl($url)
                ->withFilename($filename)
                ->withStorage($storage);

            if ($storage === 'h3') {
                if (!file_exists($localUploadDir) && !mkdir($localUploadDir, 0755, true) && !is_dir($localUploadDir)) {
                    return $this->json(['message' => 'Failed to create local target directory'], Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                $targetPath = $localUploadDir . '/' . $filename;
                $fileBuilder
                    ->withPath($targetPath);

                if (file_exists($targetPath)) {
                    $alreadyExists = true;
                }

                $uploadedFile->move($localUploadDir, $filename);
            }

            if ($storage === 'terabox') {
                $client = new Client();
                try {
                    $response = $client->post($cloudStorageProviderUrl . '/api/upload', [
                        'multipart' => [
                            [
                                'name' => 'file',
                                'contents' => fopen($uploadedFile->getPathname(), 'rb'),
                                'filename' => $uploadedFile->getClientOriginalName(),
                            ],
                            [
                                'name' => 'filename',
                                'contents' => $filename,
                            ],
                            [
                                'name' => 'storage',
                                'contents' => $storage
                            ],
                            [
                                'name' => 'path',
                                'contents' => $path
                            ]
                        ]
                    ]);
                } catch (RequestException $e) {
                    return $this->json(['message' => 'Failed to upload file to Terabox: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                $responseBody = $response->getBody()->getContents();
                $responseJson = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
                $fsId = $responseJson['fsid'];
                $size = $responseJson['size'];
                $remoteFilename = $responseJson['filename'];

                if ($fileBuilder->getSize() != $size) {
                    return $this->json(['message' => 'File upload error. Sizes are different'], Response::HTTP_BAD_REQUEST);
                }

                $fileBuilder->withPath($fsId);
                $fileBuilder->withFilename($remoteFilename);

                /*
//                trigger_error(print_r($response, true));

                //isset($responseApi['upload']['fileDetails']['size']) && $responseApi['upload']['fileDetails']['size'] ===
                //$sizeEquals = isset($responseApi['upload']['fileDetails']['size']) && $responseApi['upload']['fileDetails']['size'] === $fileBuilder->getUploadedFile()->getSize();
                /*
                $success = $responseApi['success'] ?? false;
                $message = $responseApi['message'] ?? 'Unknown error';
                $size = $responseApi['fileDetails']['size'] ?? 0;
                $fsId = $responseApi['fileDetails']['fsId'] ?? null;

//                $fileBuilder->withPath($responseApi['download']['dlink']);
//                $fileBuilder->withSize($responseApi['upload']['fileDetails']['size'] ?? 0);
//                $fileBuilder->withFilename($responseApi['upload']['fileDetails']['server_name'] ?? $filename);

                //unlink($targetPath);
                */
                $targetPath = $uploadedFile->getPathname();
            }

            if ($storage === 'google-cloud') {
                $authFileName = $this->getParameter('kernel.project_dir') . '/application_default_credentials.json';
                $clientProvider = new GoogleCloudStorageProvider($authFileName);
                $info = $clientProvider->uploadFile($fileBuilder, $path);
                $targetPath = $clientProvider->getStorageURL($info);

                $fileBuilder
                    ->withPath($targetPath);
            }

            $file = $fileBuilder->build();

            if ($file instanceof Radioplay) {
                $getId3 = new \getID3();
                $fileInfo = @$getId3->analyze($targetPath);
                $duration = 0;
                if (isset($fileInfo['playtime_seconds'])) {
                    $duration = (int)$fileInfo['playtime_seconds'];
                    $file->setDuration($duration);
                }

                $callbackData['duration'] = $duration;
            }

            try {
                $callbackData = [
                    ...$callbackData,
                    'uuid' => $realUUID->toRfc4122(),
                    'size' => $file->getSize(),
                    'type' => $file->getType(),
                    'name' => $fileBuilder->getFilename(),
                    'url' => $url,
                ];

                if ($storage !== 'h3') {
//                    unlink($targetPath);
//                    unlink($targetPath);
                }

                $entityManager->persist($file);
                $entityManager->flush();

                if ($storage === 'terabox') {
                    try {
                        unlink($targetPath);
                        //unlink($uploadedFile->getPathname());
                    } catch (FileException $e) {
                        // Log the error but don't fail the entire request
                        // You can use Symfony's logger service here if available
                        error_log('Failed to delete local file: ' . $e->getMessage());
                    }
                }

                $this->deleteDirectoryIfEmpty($localUploadDir);

            } catch (FileException $e) {
                return $this->json(['message' => 'Failed to save file: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
            $callbackData['message'] = implode(' | ', $errors);
        }

        return $this->json($callbackData, $alreadyExists ? Response::HTTP_OK : Response::HTTP_CREATED);
    }

    public function devEnvironment(): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Set headers for CORS if needed
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Content-Type: application/json');

        // Set maximum upload size (adjust as needed)
        ini_set('upload_max_filesize', '900M');
        ini_set('post_max_size', '900M');
    }

    private function sanitizeFilename($filename): string
    {
        /*
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate(
            'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
            $originalName
        );
         $extension = $file->getClientOriginalExtension();
        return $safeFilename .  '.' . $extension;
        */

        // Remove path traversal attempts
        $filename = basename($filename);
        // Replace spaces and special characters
        $filename = preg_replace('/[^\w\.\-]/', '_', $filename);
        // Limit length
        $filename = substr($filename, 0, 255);
        return $filename;
    }

    private function deleteDirectoryIfEmpty(string $dirname): bool
    {
        if (!is_dir($dirname)) {
            return false;
        }

        $iterator = new \FilesystemIterator($dirname, \FilesystemIterator::SKIP_DOTS);
        if (!$iterator->valid()) {
            rmdir($dirname);
            return true;
        }

        return false;
    }

    private function validate(Request $request): void
    {
        /*
        if (!file_exists(static::BASE_UPLOAD_DIR) && !mkdir(static::BASE_UPLOAD_DIR, 0755, true) && !is_dir(static::BASE_UPLOAD_DIR)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', static::BASE_UPLOAD_DIR));
        }
        */

        // Check if request is POST
        if ($request->getMethod() !== Request::METHOD_POST) {
            throw new Exception('Only POST method is allowed');
        }
        // Check if file was uploaded
        if (null === $request->files->get('file')) {
            throw new Exception('No file uploaded');
        }
        if (null === $request->request->get('path')) {
            throw new Exception('No path specified');
        }
        if ($request->request->get('discr') === null) {
            throw new Exception('No discriminator specified');
        }
        if (!in_array($request->request->get('discr'), ['cover', 'radioplay'], true)) {
            throw new Exception('Invalid discriminator specified');
        }
        if (null === $request->request->get('storage')) {
            throw new Exception('No storage specified');
        }

        /*
        if (!isValidFileType($fileType)) {
            throw new Exception('File type not allowed');
        }
        */
    }
}
