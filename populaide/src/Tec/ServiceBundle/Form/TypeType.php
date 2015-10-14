<?php

namespace Tec\ServiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TypeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule', 'text', array(
                'label' => false, 
                'attr' => array('max_length' => '20', 
                            'class' => 'col-xs-12 col-md-4 form-control text',
                            'placeholder' =>'Type')))
            //Submit
            ->add('Ajouter le type', 'submit', array(
                'attr' => array('class' => "btn btn-primary btn-lg col-xs-2 col-md-2 form-control",
                            'value' => 'Ajouter le type')))
            ;
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tec\ServiceBundle\Entity\Type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tec_servicebundle_type';
    }
}
