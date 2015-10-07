<?php

namespace Tec\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddresseType extends AbstractType
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
                'attr' => array('max-length' => '170', 'placeholder' => "Rue")))
            /*Numéro*/
            ->add('number', 'text', array(
                'label' => false,
                'required' => true,
                'attr' => array('placeholder' => "Numéro")))
            /*Boite*/
            ->add('box', 'text', array(
                'label' => false,
                'required' => false,
                'attr' => array('placeholder' => "Boîte")))
            /*CP*/
            ->add('cp', 'integer', array(
                'label' => false,
                'required' => true,
                'attr' => array('max-length' => '4', 'placeholder' => "Code postal")))
            /*Localité*/
            ->add('city', 'text', array(
                'label' => false,
                'required' => true,
                'attr' => array('max-length' => '80', 'placeholder' => "Localité")))
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
            'data_class' => 'Tec\UserBundle\Entity\Addresse'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tec_userbundle_addresse';
    }
}
