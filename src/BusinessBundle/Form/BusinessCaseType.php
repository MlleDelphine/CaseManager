<?php

namespace BusinessBundle\Form;

use AppBundle\Form\Type\Select2EntityType;
use CustomerBundle\Entity\AbstractClass\Customer;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
            ->add("customerType", ChoiceType::class, array(
                "label_format" => "customer_type_capitalize",
                "required" => true,
                "placeholder" => "Sélectionnez un type",
                "choices" => [
                    "corpo_groups_capitalize" => Customer::TYPE_CORPO_GROUP,
                    "corpo_sites_capitalize" => Customer::TYPE_CORPO_SITE,
                    "townships_capitalize" => Customer::TYPE_TOWN_SHIP,
                    "private_individuals_capitalize" => Customer::TYPE_PRIVATE_INDIVIDUAL,
                    "other_customers_capitalize" => Customer::TYPE_OTHER_CUSTOMER
                ],
                "mapped" => false
            ))
            ->add('customer', Select2EntityType::class, array(
                "class" => "CustomerBundle\Entity\AbstractClass\Customer",
                "choices" => [],
                "choice_label" => "htmlName",
                "label_format" => "customer_capitalize",
                "multiple" => false,
                "placeholder" => "select_type_before",
                "required" => true
            ))
            ->add('user', Select2EntityType::class, array(
                "class" => "SecurityAppBundle:User",
                "label_format" => "internal_project_manager_capitalize",
                "multiple" => false,
                "placeholder" => "select",
                "required" => true));

        $formModifierCustomer = function (FormInterface $form, $customerType = null){
            if($customerType != null){

                if($customerType == Customer::TYPE_CORPO_GROUP){
                    $className = "CustomerBundle:CorporationGroup";;
                }elseif($customerType == Customer::TYPE_CORPO_SITE){
                    $className = "CustomerBundle:CorporationSite";
                }elseif($customerType == Customer::TYPE_PRIVATE_INDIVIDUAL){
                    $className = "CustomerBundle:PrivateIndividual";
                }elseif($customerType == Customer::TYPE_TOWN_SHIP){
                    $className = "CustomerBundle:TownShip";
                }elseif($customerType == Customer::TYPE_OTHER_CUSTOMER){
                    $className = "CustomerBundle:OtherCustomer";
                }else{
                    $className = "CustomerBundle\Entity\AbstractClass\Customer";
                }
                // Create builder for customer field
                $builder = $form->getConfig()->getFormFactory()->createNamedBuilder("customer", Select2EntityType::class, null, array(
                    "class" => $className,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy("c.name", 'ASC');
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
                    //"placeholder" => "select",
                    "required" => true,
                    'auto_initialize'=>false // it's important!!!
                ));
            }else{
                // Create builder for customer field
                $builder = $form->getConfig()->getFormFactory()->createNamedBuilder("customer", Select2EntityType::class, null, array(
                    "class" => "CustomerBundle\Entity\AbstractClass\Customer",
                    "choices" => [],
                    "choice_label" => "htmlName",
                    "label_format" => "customer_capitalize",
                    "multiple" => false,
                    "placeholder" => "select_type_before",
                    "required" => true,
                    'auto_initialize'=>false // it's important!!!
                ));
            }

            // and only now you can add field to form
            $form->add($builder->getForm());
        };

        $builder->get("customerType")->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifierCustomer){
                // It's important here to fetch $event->getForm()->getData(), as $event->getData() will get you the client data (that is, the ID)
                $customerType = $event->getForm()->getData();

                $formModifierCustomer($event->getForm()->getParent(), $customerType);

            });
    }/**
 * {@inheritdoc}
 */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BusinessBundle\Entity\BusinessCase',
            "allow_extra_fields" => true
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
