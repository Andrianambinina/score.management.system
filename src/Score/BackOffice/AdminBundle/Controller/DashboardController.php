<?php

namespace App\Score\BackOffice\AdminBundle\Controller;

use App\Score\Service\MetierManagerBundle\Utils\EntityName;
use App\Score\Service\MetierManagerBundle\Utils\ServiceName;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DashboardController
 */
class DashboardController extends Controller
{
    /**
     * Display dashboard
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function indexAction()
    {
        // Get manager
        $_utils_manager = $this->get(ServiceName::SRV_METIER_UTILS);

        $_users          = $_utils_manager->getAllEntities(EntityName::USER);
        $_user_count     = count($_users);
        $_etudiants      = $_utils_manager->getAllEntities(EntityName::ETUDIANT);
        $_etudiant_count = count($_etudiants);
        $_matieres       = $_utils_manager->getAllEntities(EntityName::MATIERE);
        $_matiere_count  = count($_matieres);
        $_niveaux        = $_utils_manager->getAllEntities(EntityName::NIVEAU);
        $_niveau_count   = count($_niveaux);

        return $this->render('AdminBundle:Dashboard:dashboard.html.twig', [
            'user_count'     => $_user_count,
            'etudiant_count' => $_etudiant_count,
            'matiere_count'  => $_matiere_count,
            'niveau_count'   => $_niveau_count
        ]);
    }
}