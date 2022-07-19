<?php

namespace App\Form;

use App\Entity\Produit;
use PhpParser\Node\Stmt\ElseIf_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        if($options['ajout']  ==true  ){
        $builder
            ->add('prix', NumberType::class,[
                "required"=>false
            ])
            ->add('photo1' , FileType::class,[
                "required"=>false
            ])
            ->add('photo2' , FileType::class,[
                "required"=>false
            ])
            ->add('photo3' , FileType::class,[
                "required"=>false
            ])
            ->add('titre', TextType::class,[
                "required"=>false
            ])
            ->add('descriptif', TextareaType::class,[
                "required"=>false
            ])
            ->add('stock', NumberType::class,[
                "required"=>false
            ])
            ->add('Ajouter', SubmitType::class)
        ; 
    
    
    }elseif ($options['modification'] == true) {
      
        $builder
        ->add('prix', NumberType::class,[
            "required"=>false
        ])
        ->add('modifPhoto1' , FileType::class,[
            "required"=>false
        ])
        ->add('modifPhoto2' , FileType::class,[
            "required"=>false
        ])
        ->add('modifPhoto3' , FileType::class,[
            "required"=>false
        ])
        ->add('titre', TextType::class,[
            "required"=>false
        ])
        ->add('descriptif', TextareaType::class,[
            "required"=>false
        ])
        ->add('stock', NumberType::class,[
            "required"=>false
        ])
        ->add('Ajouter', SubmitType::class)
    ; 

    }
    



    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'ajout'=>false,
            'modification'=>false

        ]);
    }
}
