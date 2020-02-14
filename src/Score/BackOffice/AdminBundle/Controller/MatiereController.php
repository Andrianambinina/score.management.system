<?php

namespace App\Score\BackOffice\AdminBundle\Controller;

use App\Score\Service\MetierManagerBundle\Entity\Matiere;
use App\Score\Service\MetierManagerBundle\Form\MatiereType;
use App\Score\Service\MetierManagerBundle\Utils\EntityName;
use App\Score\Service\MetierManagerBundle\Utils\ServiceName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MatiereController
 */
class MatiereController extends AbstractController
{
    /**
     * Display all students
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_matieres = $_utils_manager->getAllEntities(EntityName::MATIERE);

        return $this->render('AdminBundle:Matiere:index.html.twig', [
            'matieres' => $_matieres
        ]);
    }

    /**
     * @param Matiere $_matiere
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Matiere $_matiere)
    {
        $_edit_form = $this->createEditForm($_matiere);

        return $this->render('AdminBundle:Matiere:edit.html.twig', [
            'matiere'   => $_matiere,
            'edit_form' => $_edit_form->createView()
        ]);
    }

    /**
     * Create create form
     * @param Matiere $_matiere
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCreateForm(Matiere $_matiere)
    {
        $_form = $this->createForm(MatiereType::class, $_matiere, [
            'action' => $this->generateUrl('matiere_new'),
            'method' => 'POST'
        ]);

        return $_form;
    }

    /**
     * Create new student
     * @param Request $_request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $_request)
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_matiere = new Matiere();
        $_form    = $this->createCreateForm($_matiere);
        $_form->handleRequest($_request);

        if ($_form->isSubmitted() && $_form->isValid()) {
            $_utils_manager->saveEntity($_matiere, 'new');
            $_flash_message = $this->get('translator')->trans('Ajout effectué avec succès');
            $_utils_manager->setFlash('success', $_flash_message);

            return $this->redirect($this->generateUrl('matiere_index'));
        }

        return $this->render('AdminBundle:Matiere:add.html.twig', [
            'matiere' => $_matiere,
            'form'    => $_form->createView()
        ]);
    }

    /**
     * Create edit form
     * @param Matiere $_matiere
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEditForm(Matiere $_matiere)
    {
        $_form = $this->createForm(MatiereType::class, $_matiere, [
            'action' => $this->generateUrl('matiere_update', array('id' => $_matiere->getId())),
            'method' => 'PUT'
        ]);

        return $_form;
    }

    /**
     * Update student
     * @param Request $_request
     * @param Matiere $_matiere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $_request, Matiere $_matiere)
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_edit_form = $this->createEditForm($_matiere);
        $_edit_form->handleRequest($_request);

        if ($_edit_form->isValid()) {
            $_utils_manager->saveEntity($_matiere, 'update');

            $_flash_message = $this->get('translator')->trans('Modification effectué avec succès');
            $_utils_manager->setFlash('success', $_flash_message);

            return $this->redirect($this->generateUrl('matiere_index'));
        }

        return $this->render('AdminBundle:Matiere:edit.html.twig', [
            'matiere'   => $_matiere,
            'edit_form' => $_edit_form->createView()
        ]);
    }

    /**
     * Create delete form
     * @param Matiere $_matiere
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteForm(Matiere $_matiere)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('matiere_delete', array('id' => $_matiere->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Delete student
     * @param Request $_request
     * @param Matiere $_matiere
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $_request, Matiere $_matiere)
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_form = $this->createDeleteForm($_matiere);
        $_form->handleRequest($_request);

        if ($_request->isMethod('GET') || ($_form->isSubmitted() && $_form->isValid())) {
            $_utils_manager->deleteEntity($_matiere);

            $_flash_message = $this->get('translator')->trans('Suppression effectuée avec succès');
            $_utils_manager->setFlash('success', $_flash_message);
        }

        return $this->redirect($this->generateUrl('matiere_index'));
    }
}