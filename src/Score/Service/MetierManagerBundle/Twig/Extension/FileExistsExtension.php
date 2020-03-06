<?php

namespace App\Score\Service\MetierManagerBundle\Twig\Extension;

use Twig\TwigFunction;

class FileExistsExtension extends \Twig_Extension
{
    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('file_exists', 'file_exists'),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'twig';
    }
}