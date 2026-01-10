<?php

declare(strict_types=1);

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class FileController extends AbstractController
{
    #[Route('/api/file_upload', name: 'file_upload')]
    public function index(Request $request): Response
    {
        //return $this->render('file/index.html.twig');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Set headers for CORS if needed
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Content-Type: application/json');


        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
        // Set maximum upload size (adjust as needed)
        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');

        // Base directory where files will be saved
        $BASE_UPLOAD_DIR = __DIR__ . '/../../uploads';


        if (!file_exists($BASE_UPLOAD_DIR) && !mkdir($BASE_UPLOAD_DIR, 0755, true) && !is_dir($BASE_UPLOAD_DIR)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $BASE_UPLOAD_DIR));
        }

//        $request->files

        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new Exception('Only POST method is allowed');
        }

        // Check if file was uploaded
        if (!isset($_FILES['file'])) {
            throw new Exception('No file uploaded');
        }

        // Get path from POST data
        $requestPath = isset($_POST['path']) ? trim($_POST['path']) : '';

        // Sanitize and validate the path
        if ($requestPath !== '') {
            // Remove any dangerous characters from path
            $requestPath = str_replace(['..', '//', '\\', ':'], '', $requestPath);
            $requestPath = trim($requestPath, '/');

            // Create the directory structure if it doesn't exist
            $targetDir = $BASE_UPLOAD_DIR . '/' . $requestPath;
            if (!file_exists($targetDir) && !mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $targetDir));
            }
        } else {
            $targetDir = $BASE_UPLOAD_DIR;
        }

        // Initialize response array
        $response = [
            'success' => false,
            'message' => '',
            'uuid' => '',
            'files' => []
        ];

        // Handle single file or multiple files
        $files = $_FILES['file'];

        // Generate a UUID for this upload batch
        $batchUUID = $this->generateUUID();


        // Single file
        $fileName = $this->sanitizeFilename($files['name']);
        $fileTmpName = $files['tmp_name'];
        $fileSize = $files['size'];
        $fileError = $files['error'];
        $fileType = mime_content_type($fileTmpName);

        // Check for upload errors
        if ($fileError !== UPLOAD_ERR_OK) {
            throw new Exception("Upload error: $fileError");
        }

        // Validate file type
        /*
        if (!isValidFileType($fileType)) {
            throw new Exception('File type not allowed');
        }
        */

        // Set target path
        $targetPath = $targetDir . '/' . $fileName;

        // Check if file already exists and rename if needed
        $counter = 1;
        $originalFileName = pathinfo($fileName, PATHINFO_FILENAME);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        while (file_exists($targetPath)) {
            $fileName = $originalFileName . '_' . $counter . '.' . $extension;
            $targetPath = $targetDir . '/' . $fileName;
            $counter++;
        }

        // Move uploaded file to target directory
        if (move_uploaded_file($fileTmpName, $targetPath)) {
            $response['files'][] = [
                'original_name' => $files['name'],
                'saved_name' => $fileName,
                'size' => $fileSize,
                'type' => $fileType,
                'path' => str_replace(__DIR__, '', $targetPath)
            ];
        } else {
            throw new \RuntimeException('Failed to save file');
        }

        $callbackData = [
            'uuid' => $batchUUID,
            'size' => $fileSize,
            'type' => $fileType,
            'name' => $fileName,
        ];

        return $this->json($callbackData, Response::HTTP_CREATED);
    }

    public function generateUUID()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Version 4
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Variant RFC 4122

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }


    public function sanitizeFilename($filename)
    {
        // Remove path traversal attempts
        $filename = basename($filename);
        // Replace spaces and special characters
        $filename = preg_replace('/[^\w\.\-]/', '_', $filename);
        // Limit length
        $filename = substr($filename, 0, 255);
        return $filename;
    }
}
