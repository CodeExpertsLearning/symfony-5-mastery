<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
    	$name = 'Nanderson Castro';
		$user = $this->getDoctrine()->getRepository(User::class)->find(1);

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
