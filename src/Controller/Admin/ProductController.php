<?php

namespace App\Controller\Admin;

use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/products", name="admin_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="index_products")
     */
    public function index(ProductRepository $productRepository, UploadService $uploadService)
    {
    	dump($uploadService->upload());

	    $products = $productRepository->findAll();

	    return $this->render('admin/product/index.html.twig', compact('products'));
    }

	/**
	 * @Route("/create", name="create_products")
	 */
	public function create(Request $request, EntityManagerInterface $em)
	{
		$form = $this->createForm(ProductType::class);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {

			$product = $form->getData();
			$product->setCreatedAt();
			$product->setUpdatedAt();

			$em->persist($product);
			$em->flush();

			$this->addFlash('success', 'Produto criado com sucesso!');

			return $this->redirectToRoute('admin_index_products');
		}

		return $this->render('admin/product/create.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/edit/{product}", name="edit_products")
	 */
	public function edit($product, Request $request, ProductRepository $productRepository, EntityManagerInterface $em)
	{
		$product = $productRepository->find($product);

		$form = $this->createForm(ProductType::class, $product);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {

			$product = $form->getData();
			$product->setUpdatedAt();

			$em->flush();

			$this->addFlash('success', 'Produto atualizado com sucesso!');

			return $this->redirectToRoute('admin_edit_products', ['product' => $product->getId()]);
		}

		return $this->render('admin/product/edit.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/remove/{product}", name="remove_products")
	 */
	public function remove($product, ProductRepository $productRepository)
	{
		try{
			$product = $productRepository->find($product);

		   $manager = $this->getDoctrine()->getManager();
		   $manager->remove($product);
		   $manager->flush();

		   $this->addFlash('success', 'Produto removido com sucesso!');

		   return $this->redirectToRoute('admin_index_products');

		} catch (\Exception $e) {
			die($e->getMessage());
		}
	}

}
