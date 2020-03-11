<?php

namespace App\Score\Service\UserBundle\Manager;

use App\Score\Service\MetierManagerBundle\Utils\PathName;
use App\Score\Service\MetierManagerBundle\Utils\ServiceName;
use App\Score\Service\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class UserManager
 * @package App\Score\Service\UserBundle\Manager
 */
class UserManager
{
    private $_entity_manager;
    private $_container;
    private $_web_root;

    /**
     * UserManager constructor.
     * @param EntityManager $_entity_manager
     * @param Container $_container
     * @param $_root_dir
     */
    public function __construct(EntityManager $_entity_manager, Container $_container, $_root_dir)
    {
        $this->_entity_manager = $_entity_manager;
        $this->_container      = $_container;
        $this->_web_root       = realpath($_root_dir . '/../public');
    }

    /**
     * Recuperer le repository utilisateur
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
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
     * Add user
     * @param $_user
     * @param $_form
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addUser($_user, $_form)
    {
        $_image = $_form['photo']->getData();
        if ($_image)
        	$this->upload($_user, $_image);

        return $this->saveUser($_user, 'new');
    }

    /**
     * @param $_user
     * @param $_form
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateUser($_user, $_form)
    {
        $_image = $_form['photo']->getData();
        if ($_image) {
            $this->deleteOnlyImageById($_user->getId());
            $this->upload($_user, $_image);
        }

        // Update password
        $_fos_user_manager = $this->_container->get('fos_user.user_manager');
        $_fos_user_manager->updatePassword($_user);

        return $this->saveUser($_user, 'update');
    }

    /**
     * Save user
     * @param $_user
     * @param $_action
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveUser($_user, $_action)
    {
        if ($_action == 'new') {
            $this->_entity_manager->persist($_user);
        }
        $this->_entity_manager->flush();

        return true;
    }

    /**
     * Upload file
     * @param User $_user
     * @param $_file
     */
    public function upload(User $_user, $_file)
    {
        $_directory_file = PathName::UPLOAD_PHOTO_PROFIL;

        try {
            $_original_name = $_file->getClientOriginalName();
            $_path          = pathinfo($_original_name);
            $_extension     = $_path['extension'];
            $_file_name     = md5(uniqid());

            $_filename_extension = $_file_name . '.' . $_extension;
            $_uri_file           = $_directory_file . $_filename_extension;
            $_dir                = $this->_web_root . $_directory_file;
            $_file->move(
                $_dir,
                $_filename_extension
            );

            $_user->setPhoto($_uri_file);
        } catch (FileException $_e) {
            throw new NotFoundHttpException("Error while uploading file");
        }
    }

    /**
     * Delete only image by Id
     * @param $_id
     * @throws \Doctrine\ORM\ORMException
     */
    public function deleteOnlyImageById($_id)
    {
        $_user = $this->_entity_manager->getRepository('UserBundle:User')->find($_id);
        if ($_user) {
            $_path = $this->_web_root.$_user->getPhoto();

            if (is_file($_path)) unlink($_path);
        }
    }
}