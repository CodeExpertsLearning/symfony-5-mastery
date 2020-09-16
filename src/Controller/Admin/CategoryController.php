<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/categories", name="admin_categories_")
 */
class CategoryController extends AbstractController
{
	/**
	 * @Route("/", name="index")
	 */
	public function index(CategoryRepository $categoryRepository)
	{
		$categories = $categoryRepository->findAll();

		return $this->render('admin/category/index.html.twig', compact('categories'));
	}

	/**
	 * @Route("/create", name="create")
	 */
	public function create(Request $request, EntityManagerInterface $em)
	{
		$form = $this->createForm(CategoryType::class);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {

			$category = $form->getData();
			$em->persist($category);
			$em->flush();

			$this->addFlash('success', 'Categoria criada com sucesso!');

			return $this->redirectToRoute('admin_categories_index');
		}

		return $this->render('admin/category/create.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/edit/{category}", name="edit")
	 */
	public function edit(Category $category, Request $request, EntityManagerInterface $em)
	{
		$form = $this->createForm(CategoryType::class, $category);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			$em->flush();

			$this->addFlash('success', 'Categoria atualizada com sucesso!');

			return $this->redirectToRoute('admin_categories_edit', ['category' => $category->getId()]);
		}

		return $this->render('admin/category/edit.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/remove/{category}", name="remove")
	 */
	public function remove(Category $category)
	{
		try{
			$manager = $this->getDoctrine()->getManager();
			$manager->remove($category);
			$manager->flush();

			$this->addFlash('success', 'Categoria removida com sucesso!');

			return $this->redirectToRoute('admin_categories_index');

		} catch (\Exception $e) {
			die($e->getMessage());
		}
	}
}
