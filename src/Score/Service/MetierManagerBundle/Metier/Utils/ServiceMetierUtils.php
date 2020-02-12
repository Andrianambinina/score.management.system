<?php

namespace App\Score\Service\MetierManagerBundle\Metier\Utils;

use Doctrine\ORM\EntityManager;
use App\Score\Service\MetierManagerBundle\Utils\EntityName;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ServiceMetierEquipe
 * @package App\Score\Service\MetierManagerBundle\Metier\Utils
 */
class ServiceMetierUtils
{
    private $_entity_manager;
    private $_container;

    /**
     * ServiceMetierEquipe constructor.
     * @param EntityManager $_entity_manager
     * @param Container $_container
     */
    public function __construct(EntityManager $_entity_manager, Container $_container)
    {
        $this->_entity_manager = $_entity_manager;
        $this->_container      = $_container;
    }

    /**
     * Add flash message
     * @param string $_type
     * @param string $_message
     * @return mixed
     */
    public function setFlash($_type, $_message) {
        return $this->_container->get('session')->getFlashBag()->add($_type, $_message);
    }

    /**
     * Get repository
     * @param string $_entity_name
     * @return array
     */
    public function getRepository($_entity_name)
    {
        return $this->_entity_manager->getRepository($_entity_name);
    }

    /**
     * Get all entities
     * @param string $_entity_name
     * @return array
     */
    public function getAllEntities($_entity_name)
    {
        return $this->getRepository($_entity_name)->findBy(array(), array('id' => 'DESC'));
    }

    /**
     * Get all entities by filter
     * @param string $_entity_name
     * @param array $_filter
     * @param array $_order
     * @return array
     */
    public function getAllEntitiesByOrder($_entity_name, $_filter, $_order)
    {
        return $this->getRepository($_entity_name)->findBy($_filter, $_order);
    }

    /**
     * Get entity by filter
     * @param string $_entity_name
     * @param array $_filter
     * @return array
     */
    public function getEntityByFilter($_entity_name, $_filter)
    {
        return $this->getRepository($_entity_name)->findBy($_filter);
    }

    /**
     * Get entity by filter and order
     * @param string $_entity_name
     * @param array $_filter
     * @return array
     */
    public function getEntityByFilterAndOrder($_entity_name, $_filter, $_order)
    {
        return $this->getRepository($_entity_name)->findBy($_filter, $_order);
    }

    /**
     * Get entity by filter with limit
     * @param string $_entity_name
     * @param array $_filter
     * @param array $_options
     * @return array
     */
    public function getEntityByFilterAndLimit($_entity_name, $_filter, $_options)
    {
        return $this->getRepository($_entity_name)->findBy($_filter,  array('id'=>'desc'), $_options['limit'],
            $_options['offset']);
    }

    /**
     * Get one entity by filter
     * @param string $_entity_name
     * @param array $_filter
     * @return array
     */
    public function findOneEntityByFilter($_entity_name, $_filter)
    {
        return $this->getRepository($_entity_name)->findOneBy($_filter);
    }

    /**
     * Get entity by id
     * @param string $_entity_name
     * @param integer $_id
     * @return array
     */
    public function getEntityById($_entity_name, $_id)
    {
        return $this->getRepository($_entity_name)->find($_id);
    }

    /**
     * Save entiting
     * @param Object $_entity
     * @param string $_action
     * @return object
     */
    public function saveEntity($_entity, $_action)
    {
        if ($_action == 'new') {
            $this->_entity_manager->persist($_entity);
        }
        $this->_entity_manager->flush();

        return $_entity;
    }

    /**
     * Deleting entity
     * @param Object $_entity
     * @return boolean
     */
    public function deleteEntity($_entity)
    {
        $this->_entity_manager->remove($_entity);
        $this->_entity_manager->flush();

        return true;
    }

    /**
     * Deleting by group selected
     * @param string $_entity_name
     * @param array $_ids
     * @return boolean
     */
    public function deleteGroupEntity($_entity_name, $_ids)
    {
        if (count($_ids)) {
            foreach ($_ids as $_id) {
                $_entity = $this->getEntityById($_entity_name, $_id);
                $this->deleteEntity($_entity);
            }
        }

        return true;
    }

    /**
     * Send email
     * @param mixed $_recipient
     * @param string $_subjet
     * @param string $_template
     * @param array $_data_param
     * @param mixed $_cc
     * @return bool
     */
    public function sendMail($_recipient, $_subjet, $_template, $_data_param, $_cc = null)
    {
        $_email_body         = $this->_container->get('templating')->renderResponse($_template, $_data_param);
        $_from_email_address = $this->_container->getParameter('from_email_address');
        $_from_firstname     = $this->_container->getParameter('from_firstname');

        $_email_body = implode("\n", array_slice(explode("\n", $_email_body), 4));
        $_message    = (new \Swift_Message($_subjet))
            ->setFrom(array($_from_email_address => $_from_firstname))
            ->setTo($_recipient)
            ->setBody($_email_body);

        if ($_cc != null) {
            $_message = (new \Swift_Message($_subjet))
                ->setFrom(array($_from_email_address => $_from_firstname))
                ->setTo($_recipient)
                ->setCc($_cc)
                ->setBody($_email_body);
        }

        $_message->setContentType("text/html");
        $_result = $this->_container->get('mailer')->send($_message);

        $_headers = $_message->getHeaders();
        $_headers->addIdHeader('Message-ID', uniqid() . "@domain.com");
        $_headers->addTextHeader('MIME-Version', '1.0');
        $_headers->addTextHeader('X-Mailer', 'PHP v' . phpversion());
        $_headers->addParameterizedHeader('Content-type', 'text/html', ['charset' => 'utf-8']);

        if ($_result) {
            return true;
        }

        return false;
    }
}
