<?php

namespace App\Score\BackOffice\AdminBundle\Controller;

use App\Score\Service\MetierManagerBundle\Entity\Etudiant;
use App\Score\Service\MetierManagerBundle\Form\EtudiantType;
use App\Score\Service\MetierManagerBundle\Utils\EntityName;
use App\Score\Service\MetierManagerBundle\Utils\ServiceName;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EtudiantController
 */
class EtudiantController extends Controller
{
    /**
     * Display all students
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_etudiants = $_utils_manager->getAllEntities(EntityName::ETUDIANT);

        return $this->render('AdminBundle:Etudiant:index.html.twig', [
            'etudiants' => $_etudiants
        ]);
    }

    /**
     * Create create form
     * @param Etudiant $_etudiant
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCreateForm(Etudiant $_etudiant)
    {
        $_form = $this->createForm(EtudiantType::class, $_etudiant, [
            'action' => $this->generateUrl('etudiant_new'),
            'method' => 'POST'
        ]);

        return $_form;
    }

    /**
     * @param Etudiant $_etudiant
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Etudiant $_etudiant)
    {
        $_edit_form = $this->createEditForm($_etudiant);

        return $this->render('AdminBundle:Etudiant:edit.html.twig', [
            'etudiant'  => $_etudiant,
            'edit_form' => $_edit_form->createView()
        ]);
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

        $_etudiant = new Etudiant();
        $_form     = $this->createCreateForm($_etudiant);
        $_form->handleRequest($_request);
        
        if ($_form->isSubmitted() && $_form->isValid()) {
//dd($_etudiant);
            $_utils_manager->saveEntity($_etudiant, 'new');
            $_flash_message = $this->get('translator')->trans('Ajout effectué avec succès');
            $_utils_manager->setFlash('success', $_flash_message);

            return $this->redirect($this->generateUrl('etudiant_index'));
        }

        return $this->render('AdminBundle:Etudiant:add.html.twig', [
            'etudiant' => $_etudiant,
            'form'     => $_form->createView()
        ]);
    }

    /**
     * Create edit form
     * @param Etudiant $_etudiant
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEditForm(Etudiant $_etudiant)
    {
        $_form = $this->createForm(EtudiantType::class, $_etudiant, [
            'action' => $this->generateUrl('etudiant_edit', array('id' => $_etudiant->getId())),
            'method' => 'PUT'
        ]);

        return $_form;
    }

    /**
     * Update student
     * @param Request $_request
     * @param Etudiant $_etudiant
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $_request, Etudiant $_etudiant)
    {
        die('eto');
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_edit_form = $this->createEditForm($_etudiant);
        $_edit_form->handleRequest($_request);

        if ($_edit_form->isValid()) {
//dd($_etudiant);
            $_utils_manager->saveEntity($_etudiant, 'update');

            $_flash_message = $this->get('translator')->trans('Modification effectué avec succès');
            $_utils_manager->setFlash('success', $_flash_message);

            return $this->redirect($this->generateUrl('etudiant_index'));
        }

        return $this->render('AdminBundle:Etudiant:edit.html.twig', [
            'etudiant'  => $_etudiant,
            'edit_form' => $_edit_form->createView()
        ]);
    }
}