<?php

namespace Tec\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Tec\ServiceBundle\Form\MediaType;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('first_name', 'text')
            ->add('birth_date', 'date')
            ->add('sex')
            ->add('phone', 'text')
            //->add('creation_date', 'date')
            //->add('update_date', 'date')
            ->add('disponible')
            ->add('media', new MediaType())
            ->add('Valider', 'submit');
                
                //ajout des autres attributs
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tec\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tec_userbundle_user';
    }
}
