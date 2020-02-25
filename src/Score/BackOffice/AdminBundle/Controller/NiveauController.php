<?php

namespace App\Score\BackOffice\AdminBundle\Controller;

use App\Score\Service\MetierManagerBundle\Entity\Niveau;
use App\Score\Service\MetierManagerBundle\Form\MatiereType;
use App\Score\Service\MetierManagerBundle\Form\NiveauType;
use App\Score\Service\MetierManagerBundle\Utils\EntityName;
use App\Score\Service\MetierManagerBundle\Utils\ServiceName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NiveauController
 */
class NiveauController extends AbstractController
{
    /**
     * Display all levels
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_niveaux = $_utils_manager->getAllEntities(EntityName::NIVEAU);

        return $this->render('AdminBundle:Niveau:index.html.twig', [
            'niveaux' => $_niveaux
        ]);
    }

    /**
     * @param Niveau $_niveau
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Niveau $_niveau)
    {
        $_edit_form = $this->createEditForm($_niveau);

        return $this->render('AdminBundle:Niveau:edit.html.twig', [
            'niveau'    => $_niveau,
            'edit_form' => $_edit_form->createView()
        ]);
    }

    /**
     * Create edit form
     * @param Niveau $_niveau
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEditForm(Niveau $_niveau)
    {
        $_form = $this->createForm(NiveauType::class, $_niveau, [
            'action' => $this->generateUrl('niveau_update', array('id' => $_niveau->getId())),
            'method' => 'PUT'
        ]);

        return $_form;
    }

    /**
     * Create new level
     * @param Request $_request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $_request)
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_niveau = new Niveau();
        $_form   = $this->createCreateForm($_niveau);
        $_form->handleRequest($_request);

        if ($_form->isSubmitted() && $_form->isValid()) {
            $_utils_manager->saveEntity($_niveau, 'new');
            $_flash_message = $this->get('translator')->trans('Ajout effectué avec succès');
            $_utils_manager->setFlash('success', $_flash_message);

            return $this->redirect($this->generateUrl('niveau_index'));
        }

        return $this->render('AdminBundle:Niveau:add.html.twig', [
            'niveau' => $_niveau,
            'form'   => $_form->createView()
        ]);
    }

    /**
     * Create create form
     * @param Niveau $_niveau
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCreateForm(Niveau $_niveau)
    {
        $_form = $this->createForm(NiveauType::class, $_niveau, [
            'action' => $this->generateUrl('niveau_new'),
            'method' => 'POST'
        ]);

        return $_form;
    }

    /**
     * Update level
     * @param Request $_request
     * @param Niveau $_niveau
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $_request, Niveau $_niveau)
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_edit_form = $this->createEditForm($_niveau);
        $_edit_form->handleRequest($_request);

        if ($_edit_form->isValid()) {
            $_utils_manager->saveEntity($_niveau, 'update');

            $_flash_message = $this->get('translator')->trans('Modification effectué avec succès');
            $_utils_manager->setFlash('success', $_flash_message);

            return $this->redirect($this->generateUrl('niveau_index'));
        }

        return $this->render('AdminBundle:Niveau:edit.html.twig', [
            'niveau'    => $_niveau,
            'edit_form' => $_edit_form->createView()
        ]);
    }

    /**
     * Delete level
     * @param Request $_request
     * @param Niveau $_niveau
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $_request, Niveau $_niveau)
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_form = $this->createDeleteForm($_niveau);
        $_form->handleRequest($_request);

        if ($_request->isMethod('GET') || ($_form->isSubmitted() && $_form->isValid())) {
            $_utils_manager->deleteEntity($_niveau);

            $_flash_message = $this->get('translator')->trans('Suppression effectuée avec succès');
            $_utils_manager->setFlash('success', $_flash_message);
        }

        return $this->redirect($this->generateUrl('niveau_index'));
    }

    /**
     * Create delete form
     * @param Niveau $_niveau
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteForm(Niveau $_niveau)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('niveau_delete', array('id' => $_niveau->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}