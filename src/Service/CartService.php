<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
	/**
	 * @var SessionInterface
	 */
	private $session;

	public function __construct(SessionInterface $session)
	{

		$this->session = $session;
	}

	public function getAll()
	{
		if(!$this->session->has('cart')) return [];

		$cart = $this->session->get('cart');

		if(!is_array($cart)) return [];

		return count($cart) >= 1 ? $cart : [];
	}

	public function addItem($item)
	{
		$cart = $this->getAll();
		$message = 'Produto adicionado com sucesso!';

		if(count($cart)) {
			$findSlug = array_column($cart, 'slug');

			if(in_array($item['slug'], $findSlug)) {
				$cart = $this->incrementCartSlug($cart, $item);
				$message = sprintf('Quantidade do Produto %s Adicionado com sucesso!', $item['name']);
			} else {
				array_push($cart, $item);
			}


		} else {
			$cart[] = $item;
		}

		$this->session->set('cart', $cart);

		return $message;
	}

	public function removeItem($item)
	{
		$cart = $this->getAll();

		if(count($cart) == 0) return $cart;

		$cart = array_filter($cart, function($itemArr) use($item){
			return $itemArr['slug'] != $item;
		});

		if(count($cart) == 0) {
			$this->session->remove('cart');
			return [];
		}

		$this->session->set('cart', $cart);
	}

	public function destroyCart()
	{
		$this->session->remove('cart');

		return true;
	}

	private function incrementCartSlug($cart, $item)
	{
		$cartModified = array_map(function($line) use($item){
			if($line['slug'] == $item['slug']) {
				$line['amount'] += $item['amount'];
			}
			return $line;
		}, $cart);

		return $cartModified;
	}
}