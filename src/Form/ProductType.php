<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

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
	        ->add('photos', FileType::class, [
	        	'mapped' => false,
		        'multiple' => true,
		        'constraints' => [
		        	new Assert\All(
		        		[
		        			'constraints' => [
		        			    new Assert\Image([
		        			    	'mimeTypesMessage' => 'Imagens inválidas!'
					            ])
				        ]]
			        )
		        ]
 	        ])
            ->add('category', null, [
	            'label' => 'Categorias',
	            'choice_label' => function($category) {
            	    return sprintf('(%d) %s', $category->getId(), $category->getName());
	            },
	            //'placeholder' => 'Selecione uma categoria',
	            //'multiple' => false
            ])
        ;

        $builder->get('price')
	        ->addModelTransformer(new CallbackTransformer(
		        function($price){
		        	$price = $price / 100;

			        //Para a exibicao do dado no input
			        return number_format($price, 2, ',', '.');
		        },
		        function($price){
			        $price = (float) str_replace(['.', ','], ['', '.'], $price);
			        $price = $price * 100;
			        $price = (int) ceil($price);

			        return $price;
		        }
	        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
