<?php

namespace SecurityAppBundle\Form;

use NumberFormatter;
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
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
                "label_format" => "mail_address_capitalize"))
            //"data" => "@"))
            ->add("roles", RoleType::class, array(
                "label_format" => "roles_capitalize"))
            ->add("username", TextType::class, array(
                "label_format" => "id_or_nickname_capitalize"))
            ->add("phoneNumber", TextType::class, array(
                "label_format" => "id_or_nickname_capitalize",
                "required" => false,
                "attr" => ["pattern" => "^((\+\d{2})|0)[0-9]{9}$"]))
            ->add("unitaryPrice", TextType::class,array(
                "label_format" => "hour_rate_capitalize",
                "attr" => ["required" => true, "pattern" => "^\d+(,|\.)\d{1,2}$"],
                // "currency" => "", //To remove orphan €
                //  "scale" => 2,
                "invalid_message" => "error_message_decimal_number"))
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
                "label_format" => "authorized_connection_capitalize"));

        if($options["MODE_CREATE"]){
            $builder
                ->add("plainPassword", RepeatedType::class, array(
                    "type" => PasswordType::class,
                    "options" => ["always_empty" => true, "attr" => []],
                    "data" => null,
                    // "options" => array("translation_domain" => "FOSUserBundle"),
                    "first_options" => array("label_format" => "password_capitalize"),
                    "second_options" => array("label_format" => "password_confirmation_capitalize"),
                    "invalid_message" => "fos_user.password.mismatch",
                ));
        }

        $builder->get("unitaryPrice")->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $preSubmitEvent) {
            $unitaryPriceSubmitted = $preSubmitEvent->getData(); // Submitted

            $fmt = new NumberFormatter( 'fr_FR', NumberFormatter::DECIMAL);
            $parsed = $fmt->parse($unitaryPriceSubmitted);
            if($parsed){//decimals separated by comma
                $twoDecimals = number_format($parsed, 2, '.', '');
            }else{ // decimals separated by dot
                $twoDecimals = number_format($unitaryPriceSubmitted, 2, '.', '');
            }
            $preSubmitEvent->setData($twoDecimals);
        }

        );
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
