<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

/**
 * @Route("/api/orders", name="api_orders_")
 */
class MyOrderController extends AbstractController
{
    /**
     * @Route("/", name="get", methods={"GET"})
     */
    public function index()
    {
		$user = $this->getUser();

        return $this->json([
            'data' => [
            	'orders' => $user->getOrder()
            ]
        ], 200, [], [
        	'groups' => ['user_orders']
        ]);
    }
}
