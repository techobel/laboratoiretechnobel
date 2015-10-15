<?php

/*
 * Ce fichier écrase celui du FOSUserBundle, RegistrationFormType
 */

namespace Tec\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tec\UserBundle\Form\AdresseType;
use Tec\ServiceBundle\Form\MediaType;
use Tec\UserBundle\Entity\Adresse;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UpdateFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*Nom*/
            ->add('name', 'text', array(
                'label' => false, 
                'required' => true, 
                'translation_domain' => 'FOSUserBundle', 
                'attr' => array('placeholder' => 'Nom', 
                    'class' => 'col-xs-12 col-md-4 form-control text'
                    )))
            /*Prénom*/
            ->add('first_name', 'text', array(
                'label' => false, 
                'translation_domain' => 'FOSUserBundle', 
                'attr' => array('placeholder' => 'Prénom', 
                    'class' => 'col-xs-12 col-md-4 form-control text'
                    )))
            /*Date de naissance*/
            ->add('birth_date', 'date', array(
                'label' => "Date de naissance :", 
                'required' => true, 
                'format' => 'dd MM yyyy',
                'years' => range(date('Y') - 100, date('Y')),
                'months' => range(1,12),
                'translation_domain' => 'FOSUserBundle', 
                'attr' => array('placeholder' => 'Date de naissance JJ-MM-AAAA', 
                    'class' => 'col-xs-12 col-md-4 form-control'
                )))
            /*Image*/
            ->add('media', new MediaType(), array(
                'required' => false,
                'label' => 'Choisissez une photo pour votre profil', 
                'attr' => array('class' => 'col-xs-12 col-md-4 form-control')))
            /*Pseudo*/
            ->add('username', null, array(
                'label' => false, 
                'required' => true, 
                'translation_domain' => 'FOSUserBundle', 
                'attr' => array('placeholder' => 'Choisissez un pseudo', 
                    'class' => 'col-xs-12 col-md-4 form-control text'
                    )))
            /*Password + check password*/
            ->add('plainPassword', 'repeated', array(
                'type' => 'password', 
                'options' => array('translation_domain' => 'FOSUserBundle'), 
                'first_options' => array(
                    'label' => false, 
                    'attr' => array('placeholder' => 'Choisissez un mot de passe',
                                'class' => 'col-xs-12 col-md-4 form-control text')
                    ),
                'second_options' => array(
                    'label' => false, 
                    'attr' => array('placeholder' => 'Confirmez votre mot de passe',
                                'class' => 'col-xs-12 col-md-4 form-control text')
                    ),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            /*Phone*/
            ->add('phone', 'text', array(
                'label' => false,
                'required' => false,
                'attr' => array('placeholder' => 'Numéro de téléphone',
                    'class' => 'col-xs-12 col-md-4 form-control text')
            ))
            /*Mail*/
            ->add('email', 'email', array(
                'label' => false, 
                'required' => true, 
                'translation_domain' => 'FOSUserBundle', 
                'attr' => array('placeholder' => 'Email', 
                    'class' => 'col-xs-12 col-md-4 form-control text'
                    )))            
            /*Adresse*/
            ->add('adresse', new AdresseType(), array(
                'label' => false))
            /*Disponibilité*/
            ->add('disponible', 'checkbox', array(
                'label' => 'Disponible',
                'required' => true,
                'attr' => array('class'=> "checkbox-inline")))
            //Submit
            ->add("Valider les changements", 'submit', array(
                'attr' => array('class' => "btn btn-primary col-xs-2 col-md-2 form-control",
                            'value' => 'inscrire')))
            //Reset
            ->add("Annuler", "reset", array(
                'attr' => array('class' => "btn btn-secondary col-md-2 form-control",
                            'value' => 'annuler')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array( 
            'validation_groups' => array('registration')
        ));
    }
    
    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'tec_user_registration';
    }
}
