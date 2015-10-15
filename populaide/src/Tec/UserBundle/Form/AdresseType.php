<?php

namespace Tec\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdresseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*Rue*/
            ->add('street', 'text', array(
                'label' => false,
                'required' => true,
                'attr' => array('max-length' => '170', 
                            'placeholder' => "Rue",
                            'class' => 'col-xs-12 col-md-4 form-control text')))
            /*Numéro*/
            ->add('number', 'text', array(
                'label' => false,
                'required' => true,
                'attr' => array('placeholder' => "Numéro",
                            'class' => 'col-xs-12 col-md-4 form-control text',
                            'min' => 0)))
            /*Boite*/
            ->add('box', 'text', array(
                'label' => false,
                'required' => false,
                'attr' => array('placeholder' => "Boîte",
                            'class' => 'col-xs-12 col-md-4 form-control text')))
            /*CP*/
            ->add('cp', 'integer', array(
                'label' => false,
                'required' => true,
                'attr' => array('max-length' => '4', 
                            'placeholder' => "Code postal",
                            'class' => 'col-xs-12 col-md-4 form-control text')))
            /*Localité*/
            ->add('city', 'text', array(
                'label' => false,
                'required' => true,
                'attr' => array('max-length' => '80', 
                            'placeholder' => "Localité",
                            'class' => 'col-xs-12 col-md-4 form-control text')))
            //->add('user')
            //->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tec\UserBundle\Entity\Adresse'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tec_userbundle_adresse';
    }
}
