<?php

namespace Tec\ServiceBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Sub_categorieType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => false, 
                'attr' => array('max_length' => '50', 
                            'class' => 'col-xs-12 col-md-4 form-control text',
                            'placeholder' =>'Intitulé de la sous-catégorie')))
            ->add('description', 'textarea', array(
                'label' => false,
                'attr' => array('max_length' => '150', 
                            'class' => 'col-xs-12 col-md-4 form-control', 
                            'placeholder' => "Brève description de la sous-catégorie",
                            'rows'=> "8")))
            ->add('categorie', 'entity', array(
                'label' => 'La sous-catégorie appartient à la catégorie :',
                'class' => 'TecServiceBundle:Categorie',
                'property' => 'name',
                //'multiple' => 'false',
                //'expanded' => 'false',
                'required' => 'true',
                'query_builder' => function(EntityRepository $er){
                return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }
            ))
            ->add('Enregistrer', 'submit', array(
                'attr' => array('class' => "btn btn-primary btn-lg col-xs-2 col-md-2 form-control",
                            'value' => 'Enregistrer')))
            //Reset
            ->add("Annuler", "reset", array(
                'attr' => array('class' => "btn btn-secondary col-md-2 form-control",
                            'value' => 'annuler')))    
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tec\ServiceBundle\Entity\Sub_categorie'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tec_servicebundle_sub_categorie';
    }
}
