<?php

namespace SecurityAppBundle\Form;

use SecurityAppBundle\Form\Type\RoleType;
use AppBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    const MODE_CREATE = true;
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("firstName", TextType::class, array(
                "label_format" => "firstname",
                "required" => true))
            ->add("lastName", TextType::class, array(
                "label_format" => "lastname",
                "required" => true))
            ->add("email",EmailType::class, array(
                "label_format" => "email"))
            ->add("roles", RoleType::class, array(
                "label_format" => "roles"))
            ->add("username", TextType::class, array(
                "label_format" => "id_or_nickname"))
            ->add("phoneNumber", TextType::class, array(
                "label_format" => "Téléphone",
                "required" => false,
                "attr" => ["pattern" => "^((\+\d{2})|0)[0-9]{9}$"]))
            ->add("unitaryPrice", MoneyType::class,array(
                "label_format" => "Taux horaire",
                "attr" => ["required" => true, "pattern" => "^\d+(,|.)\d{2}$"],
                "invalid_message" => "Cette valeur doit être un nombre décimal."))
            ->add("jobStatus",Select2EntityType::class, array(
                "class" => "AppBundle:JobStatus",
                "choice_label" => "name",
                "label_format" => "Poste",
                "multiple" => false,
                "placeholder" => "Sélectionner",
                "required" => false))
            ->add("team",  Select2EntityType::class, array(
                "class" => "AppBundle:Team",
                "choice_label" => "name",
                "label_format" => "Equipe",
                "multiple" => false,
                "placeholder" => "-",
                "required" => false))
            ->add("enabled", CheckboxType::class,  array(
                "label_format" => "Connexion autorisée"));

        if($options["MODE_CREATE"]){
            $builder
                ->add("plainPassword", RepeatedType::class, array(
                    "type" => PasswordType::class,
                    // "options" => array("translation_domain" => "FOSUserBundle"),
                    "first_options" => array("label_format" => "password"),
                    "second_options" => array("label_format" => "password_confirmation"),
                    "invalid_message" => "fos_user.password.mismatch",
                ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "SecurityAppBundle\Entity\User",
            "MODE_CREATE" => self::MODE_CREATE
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return "securityappbundle_user";
    }
}
