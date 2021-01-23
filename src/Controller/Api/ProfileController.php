<?php

namespace App\Controller\Api;

use App\Form\UserProfileType;
use App\Repository\UserRepository;
use App\Service\Api\FormErrorsValidation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api/profiles", name="api_profiles_")
 */
class ProfileController extends AbstractController
{
	/**
	 * @Route("/", name="get", methods={"GET"})
	 */
	public function userProfile()
	{
		$user = $this->getUser();

		return $this->json([
			'data' => [
				'profile' => $user
			]
		], 200, [], ['groups' => ['profile']]);
	}

	/**
	 * @Route("/", name="update", methods={"PUT"})
	 */
	public function profile(Request $request, FormErrorsValidation $formErrors)
	{
		$user = $this->getUser();

		$form = $this->createForm(UserProfileType::class, $user);
		$form->submit($request->request->all());

		if (!$form->isValid()) {
			return $this->json([$form->getErrors()], 400);
			return $this->json(['data' => [
				'errors' => $formErrors->getErrors($form)
			]], 400);
		}

		$this->getDoctrine()->getManager()->flush();

		return $this->json([
			'data' => [
				'message' => 'Perfil atualizado com sucesso!'
			]
		]);
	}

	/**
	 *
	 * @Route("/password", name="update_password", methods={"PUT", "PATCH"})
	 */
	public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
	{
		$plainPassword = $request->request->get('password');

		if (!$plainPassword) {
			return $this->json(['data' => [
				'message' => 'O campo senha é requerido...'
			]], 400);
		}

		$user = $this->getUser();

		$password = $passwordEncoder->encodePassword($user, $plainPassword);
		$user->setPassword($password);

		$this->getDoctrine()->getManager()->flush();

		return $this->json([
			'message' => 'Senha atualizada com sucesso!',
		]);
	}
}
