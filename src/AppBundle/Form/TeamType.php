<?php

namespace AppBundle\Form;

use AppBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('label' => 'Nom', 'required' => true))
            ->add('users',  Select2EntityType::class, array(
                'class' => 'SecurityAppBundle:User',
                'choice_label' => function ($user){
                    return $user->getFirstname().' '.$user->getLastName();
                    },
                'label' => 'utilisateurs',
                'multiple' => true,
                'by_reference' => true,
//                'placeholder' => 'Sélectionner',
                'required' => false));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Team'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_team';
    }


}
