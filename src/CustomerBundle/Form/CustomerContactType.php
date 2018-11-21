<?php

namespace CustomerBundle\Form;

use AppBundle\Form\Type\Select2ChoiceType;
use AppBundle\Form\Type\Select2EntityType;
use CustomerBundle\Entity\AbstractClass\Customer;
use CustomerBundle\Entity\CustomerContact;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('honorific', Select2ChoiceType::class, array(
                "label_format" => "honorific_capitalize",
                "required" => true,
                "placeholder" => "select_capitalize",
                "choices" => CustomerContact::getAllHonorifics()
            ))
            ->add("firstName", TextType::class, array(
                "label_format" => "firstname_capitalize",
                "required" => true))
            ->add("lastName", TextType::class, array(
                "label_format" => "lastname_capitalize",
                "required" => true))
            ->add("mailAddress",EmailType::class, array(
                "label_format" => "email_capitalize"))
            ->add("phoneNumber", TextType::class, array(
                "label_format" => "phone_number_capitalize",
                "required" => true,
                "attr" => ["pattern" => "^((\+\d{2})|0)[0-9]{9}$"])) //  "^0[0-9]{9}$"
            ->add("corporationJobStatus",Select2EntityType::class, array(
                "class" => "CustomerBundle:CorporationJobStatus",
                "choice_label" => "name",
                "label_format" => "job_capitalize",
                "multiple" => false,
                "placeholder" => "select_capitalize",
                "required" => true))
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
            ->add("customer", Select2EntityType::class, array(
                "class" => "CustomerBundle:AbstractClass\Customer",
                "choice_label" => "name",
                "label_format" => "customer_capitalize",
                "multiple" => false,
                "placeholder" => "select",
                "required" => true));
//            ->add('customer', Select2EntityType::class, array(
//                "class" => "CustomerBundle\Entity\AbstractClass\Customer",
//                "choices" => [],
//                "choice_label" => "htmlName",
//                "label_format" => "customer_capitalize",
//                "multiple" => false,
//                "placeholder" => "select_type_before_capitalize",
//                "required" => true
//            ));

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
        $formModifierCustomer = function (FormInterface $form, $customerType = null){
            if($customerType != null){
                $className = Customer::getClassNameByCustomerType($customerType);
                // Create builder for customer field
                $builder = $form->getConfig()->getFormFactory()->createNamedBuilder("customer", Select2EntityType::class, null, array(
                    "class" => $className,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy("c.name", 'ASC');
                    },
                    "group_by" => function (Customer $customerValue, $key, $value) {
                        return $customerValue->getTypeName();
                    },
                    "choice_translation_domain" => "messages",
                    "choice_label" => "htmlName",
                    "label_format" => "customer_capitalize",
                    "multiple" => false,
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
                    "placeholder" => "select_type_before_capitalize",
                    "required" => true,
                    'auto_initialize'=>false // it's important!!!
                ));
            }
            // and only now you can add field to form
            $form->add($builder->getForm());
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) use ($formModifierCustomerType){
                if($event->getData()->getCustomer()){
                    $customerType = $event->getData()->getCustomer()->getType();
                }else{
                    $customerType = null;
                }
                $formModifierCustomerType($event->getForm(), $customerType);
            });

        $builder->get("customerType")->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifierCustomer){
                // It's important here to fetch $event->getForm()->getData(), as $event->getData() will get you the client data (that is, the ID)
                $customerType = $event->getForm()->getData();
                $formModifierCustomer($event->getForm()->getParent(), $customerType);
            });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CustomerBundle\Entity\CustomerContact'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'customerbundle_customercontact';
    }
}
