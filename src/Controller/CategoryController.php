<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    public function __construct(private CategoryRepository $categoryRepository) {}

    #[Route('/category', name: 'app_category.index', methods: ['GET'])]
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('category/index.html.twig', compact('categories'));
    }

    #[Route('category/create', name: 'app_category.create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('category/create.html.twig');
    }

    #[Route('/category', name: 'app_category.store', methods: ['POST'])]
    public function store(Request $request, ValidatorInterface $validator): Response
    {
        $category = new Category();
        $category->setName($request->get('name'))
            ->setBackground($request->get('background'));

        $errors = $validator->validate($category);
        if ($errors->count() > 0) {
            return $this->redirectToRoute('app_category.index', compact('errors'));
        }

        $this->categoryRepository->create($category);

        $this->addFlash('success', 'Category saved with success');

        return $this->redirectToRoute('app_category.index');
    }

    #[Route('category/{id}', name: 'app_category.edit', methods: ['GET'])]
    public function edit(int $id): Response
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        return $this->render('category/edit.html.twig', compact('category'));
    }

    #[Route('/category/{id}', name: 'app_category.update', methods: ['PUT'])]
    public function update(Request $request, int $id, ValidatorInterface $validator): Response
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $category->setName($request->get('name'))
            ->setBackground($request->get('background'));

        $errors = $validator->validate($category);
        if ($errors->count() > 0) {
            return $this->redirectToRoute('app_category.index', compact('errors'));
        }

        $this->categoryRepository->update($category);

        $this->addFlash('success', 'Category updated with success');

        return $this->redirectToRoute('app_category.index');
    }

    #[Route('/category/{id}', name: 'app_category.delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        $this->categoryRepository->delete($category);

        $this->addFlash('success', 'Category deleted with success');

        return $this->redirectToRoute('app_category.index');
    }
}
