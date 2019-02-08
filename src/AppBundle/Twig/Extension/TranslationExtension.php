<?php
/**
 * Created by PhpStorm.
 * User: DG713C7N
 * Date: 08/02/2019
 * Time: 15:26
 */

namespace AppBundle\Twig\Extension;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Twig extension for translating arrays in Symfony2
 * Works just like the regular 'trans' filter, but takes an array as input instead
 *
 * your.examplebundle.translation_extension:
 *     class: Your\ExampleBundle\Twig\TranslationExtension
 *     tags:
 *         - { name: twig.extension }
 *     arguments:
 *         translator: "@translator"
 *
 * @author Kalman Olah <hello _at_ kalmanolah _dot_ net>
 */
class TranslationExtension extends \Twig_Extension
{
    private $translator;
    /**
     * Constructs the service
     *
     * @param TranslatorInterface $translator Translation service implementing the translation interface
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    /**
     * @{inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('transArray', array($this, 'transArrayFilter')),
        );
    }
    /**
     * Translates each element of an array using the provided translator
     *
     * @param  array  $ids        An array of message ids (may also be an array of objects that can be cast to string)
     * @param  array  $parameters An array of parameters for the messages
     * @param  string $domain     The domain for the messages
     * @param  string $locale     The locale
     *
     * @return array              An array of (hopefully) translated messages
     */
    public function transArrayFilter(
        array $ids,
        array $parameters = array(),
        string $domain = null,
        string $locale = null
    ) {
        array_walk($ids, function(&$id) use ($parameters, $domain, $locale) {
            $id = $this->translator->trans($id, $parameters, $domain, $locale);
        });
        return $ids;
    }
    /**
     * @{inheritdoc}
     */
    public function getName()
    {
        return 'translation_extension';
    }
}
