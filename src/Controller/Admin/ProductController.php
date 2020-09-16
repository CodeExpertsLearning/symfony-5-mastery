<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\ProductPhoto;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/products", name="admin_products_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ProductRepository $productRepository)
    {
	    $products = $productRepository->findAll();

	    return $this->render('admin/product/index.html.twig', compact('products'));
    }


	/**
	 * @Route("/upload")
	 */
//	public function upload(Request $request, UploadService $uploadService)
//	{
//		/** @var UploadedFile[] $photos */
//		$photos = $request->files->get('photos');
//		$uploadService->upload($photos, 'products');
//
//		return new Response('Upload');
//	}

	/**
	 * @Route("/create", name="create")
	 */
	public function create(Request $request, EntityManagerInterface $em, UploadService $uploadService)
	{
		$form = $this->createForm(ProductType::class);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {

			$product = $form->getData();

			$photosUpload = $form['photos']->getData();

			if($photosUpload) {
				$photosUpload = $uploadService->upload($photosUpload, 'products');
				$photosUpload = $this->makeProductPhotoEntities($photosUpload);

				$product->addManyProductPhoto($photosUpload);
			}

			$em->persist($product);
			$em->flush();

			$this->addFlash('success', 'Produto criado com sucesso!');

			return $this->redirectToRoute('admin_products_index');
		}

		return $this->render('admin/product/create.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/edit/{product}", name="edit")
	 */
	public function edit(Product $product, Request $request, EntityManagerInterface $em, UploadService $uploadService)
	{
		$form = $this->createForm(ProductType::class, $product);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {

			$product = $form->getData();

			$photosUpload = $form['photos']->getData();

			if($photosUpload) {
				$photosUpload = $uploadService->upload($photosUpload, 'products');
				$photosUpload = $this->makeProductPhotoEntities($photosUpload);

				$product->addManyProductPhoto($photosUpload);
			}

			$em->flush();

			$this->addFlash('success', 'Produto atualizado com sucesso!');

			return $this->redirectToRoute('admin_products_edit', ['product' => $product->getId()]);
		}

		return $this->render('admin/product/edit.html.twig', [
			'form' => $form->createView(),
			'productPhotos' => $product->getProductPhotos()
		]);
	}

	/**
	 * @Route("/remove/{product}", name="remove")
	 */
	public function remove(Product $product)
	{
		try{
		   $manager = $this->getDoctrine()->getManager();
		   $manager->remove($product);
		   $manager->flush();

		   $this->addFlash('success', 'Produto removido com sucesso!');

		   return $this->redirectToRoute('admin_products_index');

		} catch (\Exception $e) {
			die($e->getMessage());
		}
	}

	private function makeProductPhotoEntities($uploadedPhotos)
	{
		$entities = [];

		foreach($uploadedPhotos as $photo) {
			$productPhoto = new ProductPhoto();
			$productPhoto->setPhoto($photo);
			$productPhoto->setCreatedAt(new \DateTime('now', new \DateTimeZone("America/Sao_Paulo")));
			$productPhoto->setUpdatedAt(new \DateTime('now', new \DateTimeZone("America/Sao_Paulo")));
			$entities[] = $productPhoto;
		}

		return $entities;
	}
}
