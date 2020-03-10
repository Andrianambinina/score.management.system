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
        $_user_manager = $this->get(ServiceName::SRV_METIER_USER);

        // Recuperer tous les utilisateurs
        $_users = $_user_manager->getAllUser();

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
        // Recuperer manager
        $_user_manager = $this->get(ServiceName::SRV_METIER_USER);

        $_user = new User();
        $_form = $this->createCreateForm($_user);
        $_form->handleRequest($_request);

        if ($_form->isSubmitted() && $_form->isValid()) {
            $_user_manager->addUser($_user, $_form);

            //flash message
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
}