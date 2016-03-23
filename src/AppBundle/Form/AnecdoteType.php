<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnecdoteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add(
            'description',
            null,
            array(
              'attr' => array('autofocus' => true, 'rows' => 5),
            )
          )
          ->add(
            'category',
            EntityType::class,
            array(
              'class' => 'AppBundle:Category',
              'choice_label' => 'name',
              'multiple' => false,
            )
          )
          ->add(
            'nickname',
            TextType::class,
            array('required' => false)
          )
          ->add(
            'email',
            TextType::class,
            array('required' => false)
          )
          ->add(
            'save',
            SubmitType::class,
            array()
          );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
          array(
            'data_class' => 'AppBundle\Entity\Anecdote',
          )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_anecdote';
    }
}