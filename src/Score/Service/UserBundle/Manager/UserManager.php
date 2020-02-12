<?php

namespace App\Score\Service\UserBundle\Manager;

use App\Score\Service\MetierManagerBundle\Utils\ServiceName;
use App\Score\Service\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class UserManager
 * @package App\Score\Service\UserBundle\Manager
 */
class UserManager
{
    private $_entity_manager;
    private $_container;

    /**
     * UserManager constructor.
     * @param EntityManager $_entity_manager
     * @param Container $_container
     */
    public function __construct(EntityManager $_entity_manager, Container $_container)
    {
        $this->_entity_manager = $_entity_manager;
        $this->_container = $_container;
    }

    /**
     * Recuperer le repository utilisateur
     * @return array
     */
    protected function getRepository()
    {
        return $this->_entity_manager->getRepository(User::class);
    }

    /**
     * Recuperer tous les utilisateurs
     * @return array
     */
    public function getAllUser()
    {
        return $this->getRepository()->findBy(array(), array('id' => 'DESC'));
    }

    /**
     * @param User $_user
     * @param $_action
     * @return bool
     */
    public function addUser($_user, $_action)
    {
        if ($_action == 'new') {
            $this->_entity_manager->persist($_user);
        }
        $this->_entity_manager->flush();

        return true;
    }
}