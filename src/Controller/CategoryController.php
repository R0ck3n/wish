<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/add', name: 'category_add')]
    public function ajouter(EntityManagerInterface $em, Request $request, CategoryRepository $catRepo): Response
    {
        $cateList = $catRepo->findAll();

        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute("main_home");
        }


        return $this->render('category/add.html.twig', [
            'categoryForm' => $categoryForm->createView(),
            'cateList' => $cateList

        ]);
    }
}
