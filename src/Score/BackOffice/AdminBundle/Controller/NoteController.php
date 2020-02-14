<?php

namespace App\Score\BackOffice\AdminBundle\Controller;

use App\Score\Service\MetierManagerBundle\Entity\Note;
use App\Score\Service\MetierManagerBundle\Form\NoteType;
use App\Score\Service\MetierManagerBundle\Utils\EntityName;
use App\Score\Service\MetierManagerBundle\Utils\ServiceName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NoteController
 */
class NoteController extends AbstractController
{
    /**
     * Display all students
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_notes = $_utils_manager->getAllEntities(EntityName::NOTE);

        return $this->render('AdminBundle:Note:index.html.twig', [
            'notes' => $_notes
        ]);
    }

    /**
     * @param Note $_note
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Note $_note)
    {
        $_edit_form = $this->createEditForm($_note);

        return $this->render('AdminBundle:Note:edit.html.twig', [
            'note'      => $_note,
            'edit_form' => $_edit_form->createView()
        ]);
    }

    /**
     * Create edit form
     * @param Note $_note
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEditForm(Note $_note)
    {
        $_form = $this->createForm(NoteType::class, $_note, [
            'action' => $this->generateUrl('note_update', array('id' => $_note->getId())),
            'method' => 'PUT'
        ]);

        return $_form;
    }

    /**
     * Create new score
     * @param Request $_request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $_request)
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_note = new Note();
        $_form = $this->createCreateForm($_note);
        $_form->handleRequest($_request);

        if ($_form->isSubmitted() && $_form->isValid()) {
            $_utils_manager->saveEntity($_note, 'new');
            $_flash_message = $this->get('translator')->trans('Ajout effectué avec succès');
            $_utils_manager->setFlash('success', $_flash_message);

            return $this->redirect($this->generateUrl('note_index'));
        }

        return $this->render('AdminBundle:Note:add.html.twig', [
            'note' => $_note,
            'form' => $_form->createView()
        ]);
    }

    /**
     * Create create form
     * @param Note $_note
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCreateForm(Note $_note)
    {
        $_form = $this->createForm(NoteType::class, $_note, [
            'action' => $this->generateUrl('note_new'),
            'method' => 'POST'
        ]);

        return $_form;
    }

    /**
     * Update score
     * @param Request $_request
     * @param Note $_note
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $_request, Note $_note)
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_edit_form = $this->createEditForm($_note);
        $_edit_form->handleRequest($_request);

        if ($_edit_form->isValid()) {
            $_utils_manager->saveEntity($_note, 'update');

            $_flash_message = $this->get('translator')->trans('Modification effectué avec succès');
            $_utils_manager->setFlash('success', $_flash_message);

            return $this->redirect($this->generateUrl('note_index'));
        }

        return $this->render('AdminBundle:Note:edit.html.twig', [
            'note'      => $_note,
            'edit_form' => $_edit_form->createView()
        ]);
    }

    /**
     * Delete score
     * @param Request $_request
     * @param Note $_note
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $_request, Note $_note)
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_form = $this->createDeleteForm($_note);
        $_form->handleRequest($_request);

        if ($_request->isMethod('GET') || ($_form->isSubmitted() && $_form->isValid())) {
            $_utils_manager->deleteEntity($_note);

            $_flash_message = $this->get('translator')->trans('Suppression effectuée avec succès');
            $_utils_manager->setFlash('success', $_flash_message);
        }

        return $this->redirect($this->generateUrl('note_index'));
    }

    /**
     * Create delete form
     * @param Note $_note
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteForm(Note $_note)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('note_delete', array('id' => $_note->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}