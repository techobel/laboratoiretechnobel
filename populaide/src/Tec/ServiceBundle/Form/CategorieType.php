<?php

namespace Tec\ServiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategorieType extends AbstractType
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
                            'placeholder' =>'Intitulé de la catégorie')))
            ->add('description', 'textarea', array(
                'label' => false,
                'attr' => array('max_length' => '150', 
                            'class' => 'col-xs-12 col-md-4 form-control', 
                            'placeholder' => "Brève description de la catégorie",
                            'rows'=> "8")))
            ->add('media', new MediaType())
            ->add('save', 'submit');
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tec\ServiceBundle\Entity\Categorie'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tec_servicebundle_categorie';
    }
}
