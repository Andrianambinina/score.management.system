<?php

namespace App\Score\Service\MetierManagerBundle\Metier\Home;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ServiceMetierHome
 * @package App\Score\Service\MetierManagerBundle\Metier\Home
 */
class ServiceMetierHome
{
    private $_entity_manager;
    private $_container;

    /**
     * ServiceMetierHome constructor.
     * @param EntityManager $_entity_manager
     * @param Container $_container
     */
    public function __construct(EntityManager $_entity_manager, Container $_container)
    {
        $this->_entity_manager = $_entity_manager;
        $this->_container      = $_container;
    }
}