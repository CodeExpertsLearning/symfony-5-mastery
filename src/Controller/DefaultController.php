<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\{Category, Order, User, Product};

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
    	$name = 'Symfony 5 Mastery';
		$user = $this->getDoctrine()->getRepository(User::class)->find(1);
//		$order =  $this->getDoctrine()->getRepository(Order::class)->find(1);

//		$user->removeOrder($order);

//	    $this->getDoctrine()->getManager()->flush();

		//Produto e Categoria
	  //  $category = $this->getDoctrine()->getRepository(Category::class)->find(1);
		//dump($category->getProducts()->toArray());

//	    $category = new Category();
//	    $category->setName('Games');
//	    $category->setSlug('games');
//	    $category->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
//	    $category->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
//
//	    $product->setCategory($category);
//
//	    $this->getDoctrine()->getManager()->flush();


	    return $this->render('index.html.twig', compact('name', 'user'));
    }

	/**
	 * @Route("/product/{slug}", name="product_single")
	 */
	public function product($slug)
	{

		return $this->render('single.html.twig', compact('slug'));
	}
}
