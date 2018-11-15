<?php

namespace BusinessBundle\Form;

use AppBundle\Form\Type\Select2EntityType;
use CustomerBundle\Entity\AbstractClass\Customer;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BusinessCaseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, array(
                "label_format" => "naming_capitalize",
                "required" => true,
                "translation_domain" => "messages"))
            ->add('externalReference', TextType::class, array(
                "label_format" => "external_reference_capitalize",
                "required" => true))
            ->add('internalReference', TextType::class, array(
                "label_format" => "internal_reference_capitalize",
                "required" => true,
                "attr" => ["pattern" => "^(E|EC)[0-9]{8-10}[A-Z]{0-3}$"]))
            ->add('customer', Select2EntityType::class, array(
                "class" => "CustomerBundle\Entity\AbstractClass\Customer",
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy("c.name", 'ASC');
                        //->addOrderBy("c.lastName", "ASC");
                },
                "group_by" => function ($customerValue, $key, $value){
                    if($customerValue->getType() == Customer::TYPE_CORPO_GROUP){
                        return "Groupes";
                    }elseif($customerValue->getType() == Customer::TYPE_CORPO_SITE){
                        return "Sites";
                    }elseif($customerValue->getType() == Customer::TYPE_PRIVATE_INDIVIDUAL){
                        return "Particuliers";
                    }elseif($customerValue->getType() == Customer::TYPE_TOWN_SHIP){
                        return "Communes";
                    }else{
                        return "Autres";
                    }
                },
                "choice_label" => "htmlName",
                "label_format" => "customer_capitalize",
                "multiple" => false,
                "placeholder" => "select",
                "required" => true
            ))
            ->add('user', Select2EntityType::class, array(
                "class" => "SecurityAppBundle:User",
                "label_format" => "internal_project_manager_capitalize",
                "multiple" => false,
                "placeholder" => "select",
                "required" => true));
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BusinessBundle\Entity\BusinessCase'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'businessbundle_businesscase';
    }


}
