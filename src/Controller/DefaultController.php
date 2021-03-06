<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository)
    {
		$products = $productRepository->findBy([], ['createdAt' => 'DESC']);

	    return $this->render('index.html.twig', compact('products'));
    }

	/**
	 * @Route("/{slug}", name="product_single")
	 */
	public function product(Product $product)
	{
		return $this->render('single.html.twig', compact('product'));
	}
}
