<?php

namespace App\Score\BackOffice\AdminBundle\Controller;

use App\Score\Service\MetierManagerBundle\Entity\Etudiant;
use App\Score\Service\MetierManagerBundle\Form\EtudiantType;
use App\Score\Service\MetierManagerBundle\Utils\EntityName;
use App\Score\Service\MetierManagerBundle\Utils\ServiceName;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EtudiantController
 */
class EtudiantController extends AbstractController
{
    /**
     * Display all students
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('AdminBundle:Etudiant:index.html.twig');
    }

    /**
     * @param Request $_request
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function listAjaxAction(Request $_request)
    {
        // Get manager
        $_etudiant_manager = $this->get(ServiceName::SRV_METIER_ETUDIANT);

        $_start    = $_request->request->get('start');
        $_length   = $_request->request->get('length');
        $_search   = $_request->request->get('search');
        $_order_by = $_request->request->get('order_by');

        $_results  = $_etudiant_manager->getListStudents($_start, $_length, $_search, $_order_by);
        $_response = [
            'recordsTotal'    => $_results['countResult'],
            'recordsFiltered' => $_results['countResult'],
            'data'            => array_map(function ($_val) {
                return array_values($_val);
            }, $_results['results'])
        ];

        return new JsonResponse($_response);
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
            'action' => $this->generateUrl('etudiant_update', array('id' => $_etudiant->getId())),
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
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_edit_form = $this->createEditForm($_etudiant);
        $_edit_form->handleRequest($_request);

        if ($_edit_form->isValid()) {
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

    /**
     * Delete student
     * @param Request $_request
     * @param Etudiant $_etudiant
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $_request, Etudiant $_etudiant)
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_form = $this->createDeleteForm($_etudiant);
        $_form->handleRequest($_request);

        if ($_request->isMethod('GET') || ($_form->isSubmitted() && $_form->isValid())) {
            $_utils_manager->deleteEntity($_etudiant);

            $_flash_message = $this->get('translator')->trans('Suppression effectuée avec succès');
            $_utils_manager->setFlash('success', $_flash_message);
        }

        return $this->redirect($this->generateUrl('etudiant_index'));
    }

    /**
     * Create delete form
     * @param Etudiant $_etudiant
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteForm(Etudiant $_etudiant)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('etudiant_delete', array('id' => $_etudiant->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Show student info
     * @param Etudiant $_etudiant
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function showAction(Etudiant $_etudiant)
    {
        // Get manager
        $_etudiant_manager = $this->get(ServiceName::SRV_METIER_ETUDIANT);

        $_notes   = $_etudiant_manager->getScoreByStudent($_etudiant);
        $_average = $_etudiant_manager->getAverageByStudent($_etudiant);

        return $this->render('AdminBundle:Etudiant:show.html.twig', [
            'etudiant' => $_etudiant,
            'notes'    => $_notes,
            'average'  => $_average
        ]);
    }

    /**
     * Generer pdf document
     * @param Etudiant $_etudiant
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function genererPdfAction(Etudiant $_etudiant)
    {
        // Get manager
        $_etudiant_manager = $this->get(ServiceName::SRV_METIER_ETUDIANT);

        $_options = new Options();
        $_options->set('isRemoteEnabled', TRUE);
        $_document = new Dompdf($_options);

        $_notes   = $_etudiant_manager->getScoreByStudent($_etudiant);
        $_average = $_etudiant_manager->getAverageByStudent($_etudiant);

        $_template = $this->renderView('AdminBundle:Etudiant:template.html.twig', [
            'etudiant' => $_etudiant,
            'notes'    => $_notes,
            'average'  => $_average
        ]);

        $_document->loadHtml($_template);
        $_document->setPaper('A4', 'landscape');
        $_document->render();
        $_document->stream("Bulletin des notes", ['Attachment' => 0]);

        exit(0);
    }
}
