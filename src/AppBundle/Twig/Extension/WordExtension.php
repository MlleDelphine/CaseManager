<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 28/03/2018
 * Time: 15:20
 */
namespace AppBundle\Twig\Extension;

use Symfony\Component\Intl\Intl;


class WordExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('ucfirst',
                array($this, 'ucFirst'), array('needs_environment' => true)
            ),
        );
    }

    public function ucFirst(\Twig_Environment $env, $string)
    {
        if (null !== $charset = $env->getCharset()) {
            $prefix = mb_strtoupper(mb_substr($string, 0, 1, $charset), $charset);
            $suffix = mb_substr($string, 1, mb_strlen($string, $charset));
            return sprintf('%s%s', $prefix, $suffix);
        }
        return ucfirst(strtolower($string));
    }

    public function getName()
    {
        return "ucfirst_extension";
    }
}