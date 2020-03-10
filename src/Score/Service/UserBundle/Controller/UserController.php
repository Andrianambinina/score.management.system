<?php

namespace App\Score\Service\UserBundle\Controller;

use App\Score\Service\MetierManagerBundle\Utils\EntityName;
use App\Score\Service\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Score\Service\MetierManagerBundle\Utils\ServiceName;
use App\Score\Service\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package App\Score\Service\UserBundle\Controller
 */
class UserController extends Controller
{
    /**
     * Display all users
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        // Recuperer manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        // Recuperer tous les utilisateurs
        $_users = $_utils_manager->getAllEntities(EntityName::USER);

        return $this->render('UserBundle:User:index.html.twig', array(
            'users' => $_users,
        ));
    }

    /**
     * Create new user
     * @param Request $_request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function newAction(Request $_request)
    {
        // Get manager
        $_user_manager  = $this->get(ServiceName::SRV_METIER_USER);
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_user = new User();
        $_form = $this->createCreateForm($_user);
        $_form->handleRequest($_request);

        if ($_form->isSubmitted() && $_form->isValid()) {
            $_user_manager->addUser($_user, $_form);
            $_flash_message = "Enregistrement effectué avec succès";
            $_utils_manager->setFlash('success', $_flash_message);

            return $this->redirectToRoute('user_index');
        }

        return $this->render('UserBundle:User:add.html.twig', array(
            'user' => $_user,
            'form' => $_form->createView(),
        ));
    }

    /**
     * Create create form
     * @param User $_user
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCreateForm(User $_user)
    {
        $_form = $this->createForm(UserType::class, $_user, array(
            'action' => $this->generateUrl('user_new'),
            'method' => 'POST',
        ));

        return $_form;
    }

    /**
     * Edit user
     * @param User $_user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(User $_user)
    {
        $_edit_form = $this->createEditForm($_user);

        return $this->render('@User/User/edit.html.twig', [
            'user'      => $_user,
            'edit_form' => $_edit_form->createView()
        ]);
    }

    /**
     * Create edit form
     * @param User $_user
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEditForm(User $_user)
    {
        $_form = $this->createForm(UserType::class, $_user, array(
            'action' => $this->generateUrl('user_update', array('id' => $_user->getId())),
            'method' => 'PUT',
        ));

        return $_form;
    }

    /**
     * Updata user
     * @param User $_user
     * @param Request $_request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateAction(User $_user, Request $_request)
    {
        // Get manager
        $_user_manager  = $this->get(ServiceName::SRV_METIER_USER);
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_form = $this->createEditForm($_user);
        $_form->handleRequest($_request);

        if ($_form->isValid()) {
            $_user_manager->updateUser($_user, $_form);
            $_flash_message = "Modification effectué avec succès";
            $_utils_manager->setFlash('success', $_flash_message);

            return $this->redirectToRoute('user_index');
        }

        return $this->render('UserBundle:User:edit.html.twig', array(
            'user'      => $_user,
            'edit_form' => $_form->createView(),
        ));
    }
}