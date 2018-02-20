<?php
/**
 * Created by PhpStorm.
 * User: BDHK6353
 * Date: 12/04/2017
 * Time: 17:31
 */

namespace AppBundle\Form\Type;

use Genemu\Bundle\FormBundle\Form\Core\Type\TinymceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomTinyMceType extends TinymceType
{
    protected $options;
//    /**
//     * The constructor
//     */
    public function __construct()
    {
        $this->options = array("configs" =>
            [
                "entity_encoding" => "raw",
                "plugins" => "advlist autolink link image lists charmap print preview"
            ]);
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->options = $options;
    }

    public function buildView(FormView $view, FormInterface $form, array $options){

        $view->vars['configs'] = $options['configs'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $configs = array_merge(array(
            'language' => \Locale::getDefault(),
        ), $this->options);

        $resolver
            ->setDefaults(array(
                'configs' => array('theme' => 'modern'),
                'required' => false,
            ))
            ->setAllowedTypes('configs', 'array')
            ->setNormalizer('configs',
                function (Options $options, $value) use ($configs) {
                    return array_merge($configs['configs'], $value);
                }
            )
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\TextareaType';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'genemu_tinymce';
    }
}