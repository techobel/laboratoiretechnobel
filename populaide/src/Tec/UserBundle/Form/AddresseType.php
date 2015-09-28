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
            ->add('street', 'text', array('attr' => array('max_length' => '170')))
            ->add('number', 'integer')
            ->add('box', 'integer')
            ->add('city', 'text', array('attr' => array('max_length' => '80')))
            ->add('cp', 'integer')
            //->add('user')
            ->add('save', 'submit')
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
