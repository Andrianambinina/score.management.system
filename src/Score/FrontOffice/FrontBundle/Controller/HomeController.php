<?php 

namespace App\Score\FrontOffice\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class HomeController
 * @package App\Score\FrontOffice\FrontBundle\Controller
 */
class HomeController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function indexAction()
	{
		return $this->render('FrontBundle:Home:index.html.twig');
	}
}