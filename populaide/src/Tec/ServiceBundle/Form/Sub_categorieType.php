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
            ->add('name', 'text', array('attr' => array('max_length' => '50')))
            ->add('description', 'textarea', array('attr' => array('max_length' => '150')))
            ->add('categorie', 'entity', array(
                'class' => 'TecServiceBundle:Categorie',
                'property' => 'categorie',
                'multiple' => 'false',
                'expanded' => 'true',
                'query_builder' => function(EntityRepository $er){
                return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                }
            ))
            ->add('save', 'submit')
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
