<?php
/**
 * Description du fichier : Ce fichier contient le formulaire dynamique de recherche
 *
 * @category   Fonctions du Form
 * @package    App
 * @subpackage Form
 * @author     Elouan Teissere
 * @version    1.0 - 08/05/2023
 *
 */

namespace App\Form;


use Doctrine\ORM\EntityRepository;

use App\Entity\Seismes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    // On crée un formulaire de recherche dynamique
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // On ajoute un champ texte pour le pays alloué dynamiquement avec doctrine
            ->add('pays', EntityType::class, [
                'class' => Seismes::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->groupBy('s.pays')
                        ->orderBy('s.pays', 'ASC');
                },
                'choice_label' => 'pays',
                'placeholder' => 'Choisir un pays',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'true',
                    'minlength' => 3,
                ],
            ])
            // On ajoute un champ texte pour l'intensité maximale
            ->add('maxIntensity', ChoiceType::class, [
                'choices' => [
                    'Choisir une intensité maximale' => null,
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10,
                ],
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seismes::class,
        ]);
    }
}
