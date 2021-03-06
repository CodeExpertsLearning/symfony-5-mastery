<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, ['label' => 'Nome'])
            ->add('lastName', null, ['label' => 'Sobrenome'])
            ->add('email', null, ['label' => 'Email'])
            ->add('password', PasswordType::class,  [
            	'mapped'=> false,
	            'label' => 'Sua Senha'
            ])
            ->add('address', AddressFormType::class, ['label' => 'Dados Entrega'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
