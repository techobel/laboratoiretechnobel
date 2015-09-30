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
        $builder
            ->add('title', 'text', array('attr' => array('max_length' => '70', 'class' => 'col-xs-12 col-md-4 form-control')))
            ->add('description', 'textarea', array('attr' => array('max_length' => '255')))
            ->add('remarques', 'textarea', array('attr' => array('max_length' => '150')))
            ->add('perimetre', 'integer')
            ->add('aide', 'checkbox', array('label' => 'Aide à la rédaction?', 'required' => false))
            ->add('diffusion', 'checkbox', array('required' => false))
            //->add('active')
            //->add('creationDate')
            //->add('updateDate')
            //->add('deleteDate')
            //->add('type')
                
            //à tester (generer une liste avec les val de la BD
            ->add('sub_categorie', 'entity', array(
                'class' => 'TecServiceBundle:Sub_categorie',
                'property' => 'name',
                //'required' => 'true',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('u')
                            ->orderBy('u.name', 'ASC');
                }
            ))
            //->add('poste')
            ->add('save', 'submit');
        
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
