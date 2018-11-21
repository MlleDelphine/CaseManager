<?php

namespace BusinessBundle\Form;

use AppBundle\Form\Type\Select2EntityType;
use CustomerBundle\Entity\AbstractClass\Customer;
use CustomerBundle\Entity\CustomerContact;
use Doctrine\ORM\EntityRepository;
use SecurityAppBundle\Entity\User;
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
                "expanded" => true,
                "multiple" => false,
                "choices" => [
                    "corpo_groups_capitalize" => Customer::TYPE_CORPO_GROUP,
                    "corpo_sites_capitalize" => Customer::TYPE_CORPO_SITE,
                    "townships_capitalize" => Customer::TYPE_TOWN_SHIP,
                    "private_individuals_capitalize" => Customer::TYPE_PRIVATE_INDIVIDUAL,
                    "other_customers_capitalize" => Customer::TYPE_OTHER_CUSTOMER
                ],
                "mapped" => false,
                "attr" => ["color" => "flat-green", "splittedBy" => "3"]
            ))
            ->add('customer', Select2EntityType::class, array(
                "class" => "CustomerBundle\Entity\AbstractClass\Customer",
                "choices" => [],
                "choice_label" => "htmlName",
                "label_format" => "customer_capitalize",
                "multiple" => false,
                "placeholder" => "select_type_before_capitalize",
                "required" => true
            ))
            ->add('customerContact', Select2EntityType::class, array(
                "class" => "CustomerBundle:CustomerContact",
                "label_format" => "customer_project_manager_capitalize",
                "group_by" => function (CustomerContact $customerContactValue, $key, $value){
                    return ($customerContactValue->getCorporationJobStatus()) ? $customerContactValue->getCorporationJobStatus() : "undefined_capitalize";
                },
                "choice_translation_domain" => "messages",
                "multiple" => false,
                "placeholder" => "select",
                "required" => true))
            ->add('user', Select2EntityType::class, array(
                "class" => "SecurityAppBundle:User",
                "label_format" => "internal_project_manager_capitalize",
                "group_by" => function (User $userValue, $key, $value){
                    return ($userValue->getJobStatus()) ? $userValue->getJobStatus() : "undefined_capitalize";
                },
                "choice_translation_domain" => "messages",
                "multiple" => false,
                "placeholder" => "select",
                "required" => true));

        $formModifierCustomerType = function (FormInterface $form, $customerType = null){
            if($customerType != null){
                // Create builder for customer field
                $builder = $form->getConfig()->getFormFactory()->createNamedBuilder("customerType", ChoiceType::class, null, array(
                    "label_format" => "customer_type_capitalize",
                    "required" => true,
                    "expanded" => true,
                    "multiple" => false,
                    "data" => $customerType,
                    "choices" => [
                        "corpo_groups_capitalize" => Customer::TYPE_CORPO_GROUP,
                        "corpo_sites_capitalize" => Customer::TYPE_CORPO_SITE,
                        "townships_capitalize" => Customer::TYPE_TOWN_SHIP,
                        "private_individuals_capitalize" => Customer::TYPE_PRIVATE_INDIVIDUAL,
                        "other_customers_capitalize" => Customer::TYPE_OTHER_CUSTOMER
                    ],
                    "mapped" => false,
                    "attr" => ["color" => "flat-green", "splittedBy" => "3"],
                    'auto_initialize'=>false // it's important!!!
                ));
                // and only now you can add field to form
                $form->add($builder->getForm());
            }else {
            }
        };

        $formModifierCustomer = function (FormInterface $form, $formModifierCustomerContact, $customerType = null){
            if($customerType != null){
                $className = Customer::getClassNameByCustomerType($customerType);
                // Create builder for customer field
                $builder = $form->getConfig()->getFormFactory()->createNamedBuilder("customer", Select2EntityType::class, null, array(
                    "class" => $className,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy("c.name", 'ASC');
                    },
                    "group_by" => function (Customer $customerValue, $key, $value){
                        return $customerValue->getTypeName();
                    },
                    "choice_translation_domain" => "messages",
                    "choice_label" => "htmlName",
                    "label_format" => "customer_capitalize",
                    "multiple" => false,
                    //"placeholder" => "select",
                    "required" => true,
                    'auto_initialize' => false // it's important!!!
                ));
            }else{
                // Create builder for customer field
                $builder = $form->getConfig()->getFormFactory()->createNamedBuilder("customer", Select2EntityType::class, null, array(
                    "class" => "CustomerBundle\Entity\AbstractClass\Customer",
                    "choices" => [],
                    "choice_label" => "htmlName",
                    "label_format" => "customer_capitalize",
                    "multiple" => false,
                    "placeholder" => "select_type_before_capitalize",
                    "required" => true,
                    'auto_initialize'=>false // it's important!!!
                ));
            }

            $builder->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifierCustomerContact){
                    // It's important here to fetch $event->getForm()->getData(), as $event->getData() will get you the client data (that is, the ID)
                    $customer = $event->getForm()->getData();
                    $formModifierCustomerContact($event->getForm()->getParent(), $customer);
                });
            // and only now you can add field to form
            $form->add($builder->getForm());
        };

        $formModifierCustomerContact = function(FormInterface $form, Customer $customerID = null){
            if($customerID != null){
                // Create builder for customer field
                $builder = $form->getConfig()->getFormFactory()->createNamedBuilder("customerContact", Select2EntityType::class, null, array(
                    "class" => "CustomerBundle:CustomerContact",
                    'query_builder' => function (EntityRepository $er) use ($customerID){
                        return $er->getAllContactsByCustomerHTML($customerID);
                    },
                    "group_by" => function (CustomerContact $customerContactValue, $key, $value) {
                        return $customerContactValue->getCorporationJobStatus();
                    },
                    "choice_translation_domain" => "messages",
                    "label_format" => "customer_project_manager_capitalize",
                    "multiple" => false,
                    "required" => true,
                    'auto_initialize'=>false // it's important!!!
                ));
                // and only now you can add field to form
                $form->add($builder->getForm());
            }else {
                // Create builder for customer field
                $builder = $form->getConfig()->getFormFactory()->createNamedBuilder("customerContact", Select2EntityType::class, null, array(
                    "class" => "CustomerBundle\Entity\AbstractClass\Customer",
                    "choices" => [],
                    "label_format" => "customer_project_manager_capitalize",
                    "multiple" => false,
                    "placeholder" => "select",
                    "required" => true,
                    'auto_initialize'=>false // it's important!!!
                ));
            }
            // and only now you can add field to form
            $form->add($builder->getForm());
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) use ($formModifierCustomerType, $formModifierCustomerContact){
                $businessCase = $event->getData();
                if($event->getData()->getCustomer()){
                    $customer = $businessCase->getCustomer();
                    $customerType = $customer->getType();
                }else{
                    $customer = null;
                    $customerType = null;
                }
                $formModifierCustomerType($event->getForm(), $customerType);
                $formModifierCustomerContact($event->getForm(), $customer);
            });

        $builder->get("customerType")->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifierCustomer, $formModifierCustomerContact){
                // It's important here to fetch $event->getForm()->getData(), as $event->getData() will get you the client data (that is, the ID)
                $customerType = $event->getForm()->getData();
                $formModifierCustomer($event->getForm()->getParent(), $formModifierCustomerContact, $customerType);;
            });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BusinessBundle\Entity\BusinessCase',
            "allow_extra_fields" => true,
            "translation_domain" => "messages",
            "choice_translation_domain" => "messages"
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
