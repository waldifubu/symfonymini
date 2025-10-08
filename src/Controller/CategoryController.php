<?php

namespace App\Controller;

use App\Enum\MainCategoryEnum;
use App\Enum\SubCategoryEnum;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/category')]
class CategoryController extends AbstractController
{
    #[Route(name: 'app_category_index', methods: ['GET'])]
    public function index(): Response
    {
        return new JsonResponse(MainCategoryEnum::getAsArray(), json: false);
    }

    #[Route(path: '/{category}', name: 'app_category_show', methods: ['GET'])]
    public function show(string $category): Response
    {
        if (!$this->isValidEnumValue($category, MainCategoryEnum::class)) {
            return new JsonResponse(['error' => 'Invalid category'], Response::HTTP_BAD_REQUEST);
        }

        $method = $this->determineMethodName($category);

        if (method_exists(SubCategoryEnum::class, $method)) {
            $jsonResponse = new JsonResponse(SubCategoryEnum::$method(), json: false);

            return $jsonResponse->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        return new JsonResponse([SubCategoryEnum::NONE->name => SubCategoryEnum::NONE], status: Response::HTTP_OK, json: false);
    }

    public function determineMethodName(string $category): string
    {
        $str = str_replace('_', '', ucwords(strtolower($category), '_'));
        $str = ucfirst($str);
        return 'get' . $str;
    }


    public function isValidEnumValue(string $value, string $enumClass): bool
    {
        if (!enum_exists($enumClass)) {
            return false;
        }

        return in_array($value, array_column($enumClass::cases(), 'name'), true);
    }
}
