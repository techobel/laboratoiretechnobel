<?php

namespace Tec\ServiceBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnnonceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Placeholder du champ description
        $placeholder = 'Rédigez ici votre annonce si vous n\'avez pas coché "Aide à la rédaction". '
                . 'Si vous avez fait appel à notre aide rédactionnelle, donnez-nous quelques mots-clés '
                . 'décrivant votre annonce et nous nous chargerons de la rédiger.';
        
        $builder
            //Type
            ->add('type', 'entity', array(
                'label' => false,
                'class' => 'TecServiceBundle:Type',
                'property' => 'intitule',
                'query_builder' =>function(EntityRepository $er){
                    return $er->createQueryBuilder('u')
                            ->orderBy('u.intitule', 'ASC');
                }
            ))  
            //Catégorie
            //à tester (generer une liste avec les val de la BD
            ->add('sub_categorie', 'entity', array(
                'label' => false,
                'class' => 'TecServiceBundle:Sub_categorie',
                'property' => 'name',
                'required' => 'true',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('u')
                            ->orderBy('u.name', 'ASC');
                }
            ))
            //Titre
            ->add('title', 'text', array(
                'label' => false, 
                'attr' => array('max_length' => '70', 
                            'class' => 'col-xs-12 col-md-4 form-control text',
                            'placeholder' =>'Titre')))
            //Aide à la rédaction
            ->add('aide', 'checkbox', array(
                'label' => 'Aide à la rédaction', 
                'attr' => array('class' => 'checkbox-inline'),
                'required' => false))
            //Description
            ->add('description', 'textarea', array(
                'label' => false,
                'attr' => array('max_length' => '255', 
                            'class' => 'col-xs-12 col-md-4 form-control', 
                            'placeholder' => $placeholder,
                            'rows'=> "8")))
            //Périmètre
            ->add('perimetre', 'integer', array(
                'label' => 'Je me déplace dans un rayon de ',
                'attr' => array('class' => 'col-xs-12 col-md-4 form-control')))
            //Remarques
            ->add('remarques', 'textarea', array(
                'label' => false,
                'attr' => array('max_length' => '150', 
                            'class' => 'col-xs-12 col-md-4 form-control', 
                            'placeholder' =>'Remarques',
                            'rows'=> "4")))
            //Diffusion
            ->add('diffusion', 'checkbox', array(
                'required' => false))
            //->add('active')
            //->add('creationDate')
            //->add('updateDate')
            //->add('deleteDate')
            //->add('poste')
            ->add('visualiser', 'submit', array(
                'attr' => array('class' => "btn btn-primary btn-lg col-xs-2 col-md-2 form-control",
                            'value' => 'visualiser',
                            'data-toggle' => "modal",
                            'data-target' => "#myModal")));
             
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tec\ServiceBundle\Entity\Annonce'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tec_servicebundle_annonce';
    }
}
