<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\BlogPost;
use App\Form\BlogPostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class BlogPostController extends AbstractController
{
    #[Route('/blog-post')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $blogPost = new BlogPost();
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($blogPost);
            $entityManager->flush();

            return $this->redirectToRoute('app_home2');
        }

        return $this->render('blog_post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
