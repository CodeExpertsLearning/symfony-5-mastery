<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
            	'label' => 'Nome Produto'
            ])
            ->add('description', null, [
	            'label' => 'Descrição Rápida'
            ])
            ->add('body', null, [
	            'label' => 'Conteúdo'
            ])
            ->add('price', TextType::class, [
	            'label' => 'Preço'
            ])
            ->add('slug')
            ->add('category', null, [
	            'label' => 'Categorias',
	            'choice_label' => function($category) {
            	    return sprintf('(%d) %s', $category->getId(), $category->getName());
	            },
	            //'placeholder' => 'Selecione uma categoria',
	            //'multiple' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
