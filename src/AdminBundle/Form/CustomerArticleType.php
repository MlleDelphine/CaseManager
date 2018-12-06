<?php

namespace AdminBundle\Form;

use AdminBundle\Entity\CustomerChapter;
use AppBundle\Form\Type\CustomTinyMceType;
use AppBundle\Form\Type\Select2EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerArticleType extends AbstractType
{
    const EDIT_MODE = "EDITION";
    const CREATE_MODE = "CREATION";
    const POP_UP_MODE = "POPUP";
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                "label" => "name_capitalize",
                "required" => true))
            ->add("designation", CustomTinyMceType::class, array(
                "label_format" => "naming_capitalize",
                "configs" => ["height" => 300, "language_url" => "/bundles/app/js/tinymce/langs/fr_FR.js"],
                "required" => false,
                "attr" => ["class" => "tinymce-textarea"]))
            ->add("color", TextType::class, array(
                "label_format" => "tag_color_capitalize",
                "required" => true,
                "attr" => ["class" => "input-group colorpicker-element"]
            ))
            ->add('reference', TextType::class, array(
                "label" => "reference_capitalize",
                "required" => true))
            ->add("unitTimePoints", CollectionType::class, array(
                "entry_type" => UnitTimePointType::class,
                "entry_options" => ["label" => false],
                "allow_add" => true,
                "allow_delete" => true,
                "delete_empty" => true,
                "prototype" => true,
                "by_reference" => false, //ensures that the setter is called in all UnitTimePrices
                "attr" => [
                    "class" => "item-collection col-md-12 col-xs-12",
                ],
                "label_format" => "points_definition_capitalize",
                "required" => false));

        if($options["mode"] == self::POP_UP_MODE){
            $builder
                ->add('customerChapter', Select2EntityType::class, array(
                    "class" => "AdminBundle:CustomerChapter",
                    "group_by" => function (CustomerChapter $customerValue, $key, $value) {
                        return $customerValue->getCustomerSerial();
                    },
                    "choice_translation_domain" => "messages",
                    // "choice_label" => "htmlName",
                    "label_format" => "customer_chapter_capitalize",
                    "multiple" => false,
                    "placeholder" => "---",
                    "required" => true,
                    "attr" => ["readonly" => true, "class" => "form-control col-md-12 col-xs-12 disabled" ]
                ));
        }

    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AdminBundle\Entity\CustomerArticle',
            "mode" => self::CREATE_MODE
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_customerarticle';
    }


}
