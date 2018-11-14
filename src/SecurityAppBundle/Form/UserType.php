<?php

namespace SecurityAppBundle\Form;

use AdminBundle\Entity\AbstractClass\Unit;
use AppBundle\Form\TimePriceType;
use SecurityAppBundle\Form\Type\RoleType;
use AppBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
                "label_format" => "firstname_capitalize",
                "required" => true))
            ->add("lastName", TextType::class, array(
                "label_format" => "lastname_capitalize",
                "required" => true))
            ->add("email",EmailType::class, array(
                "label_format" => "mail_address_capitalize",
                "attr" => ["autocomplete "=> "new-password"]))
            ->add("roles", RoleType::class, array(
                "label_format" => "roles_capitalize"))
            ->add("username", TextType::class, array(
                "label_format" => "id_or_nickname_capitalize"))
            ->add("phoneNumber", TextType::class, array(
                "label_format" => "phone_number_capitalize",
                "required" => false,
                "attr" => ["pattern" => "^((\+\d{2})|0)[0-9]{9}$"]))
            ->add("jobStatus",Select2EntityType::class, array(
                "class" => "AppBundle:JobStatus",
                "choice_label" => "name",
                "label_format" => "internal_job_capitalize",
                "multiple" => false,
                "placeholder" => "select",
                "required" => false))
            ->add("team",  Select2EntityType::class, array(
                "class" => "AppBundle:Team",
                "choice_label" => "name",
                "label_format" => "team_capitalize",
                "multiple" => false,
                "placeholder" => "select",
                "required" => false))
            ->add("enabled", CheckboxType::class,  array(
                "label_format" => "authorized_connection_capitalize"))
            ->add("unit", ChoiceType::class, array("label_format" => "Unité de mesure", "required" => true,
                "choices" =>
                    Unit::getSubUnitsByMainKey("Temps")))
            ->add("timePrices", CollectionType::class, [
                "entry_type" => TimePriceType::class,
                "entry_options" => ["label" => false],
                "allow_add" => true,
                "allow_delete" => true,
                "delete_empty" => true,
                "prototype" => true,
                "by_reference" => false, //ensures that the setter is called in all UnitTimePrices
                "attr" => [
                    "class" => "item-collection col-md-12 col-xs-12",
                ],
                "label_format" => "hour_rates_capitalize",
                "required" => false]);

        if($options["MODE_CREATE"]){
            $builder
                ->add("plainPassword", RepeatedType::class, array(
                    "type" => PasswordType::class,
                    "options" => ["always_empty" => true, "attr" => ["autocomplete "=> "new-password"]],
                    "first_options" => array("label_format" => "password_capitalize"),
                    "second_options" => array("label_format" => "password_confirmation_capitalize"),
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
