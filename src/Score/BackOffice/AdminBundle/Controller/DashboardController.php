<?php

namespace App\Score\BackOffice\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DashboardController
 */
class DashboardController extends Controller
{
    /**
     * Afficher le tableau de bord
     */
    public function indexAction()
    {
        return $this->render('AdminBundle:Dashboard:dashboard.html.twig');
    }
}