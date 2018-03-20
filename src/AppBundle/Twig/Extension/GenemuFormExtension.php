<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 20/03/2018
 * Time: 15:58
 */

namespace AppBundle\Twig\Extension;


use Genemu\Bundle\FormBundle\Twig\Extension\FormExtension;
use Symfony\Component\Form\FormRenderer;

class GenemuFormExtension extends FormExtension {

    /**
     * Constructs.
     *
     * @param TwigRendererInterface $renderer
     */
    public function __construct(FormRenderer $renderer)
    {
        $this->renderer = $renderer;
    }
}