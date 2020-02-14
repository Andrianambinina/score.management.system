<?php

namespace App\Score\Service\MetierManagerBundle\Metier\Etudiant;

use App\Score\Service\MetierManagerBundle\Entity\Etudiant;
use App\Score\Service\MetierManagerBundle\Utils\EntityName;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ServiceMetierEtudiant
 * @package App\Score\Service\MetierManagerBundle\Metier\Etudiant
 */
class ServiceMetierEtudiant
{
    private $_entity_manager;
    private $_container;

    /**
     * ServiceMetierEtudiant constructor.
     * @param EntityManager $_entity_manager
     * @param Container $_container
     */
    public function __construct(EntityManager $_entity_manager, Container $_container)
    {
        $this->_entity_manager = $_entity_manager;
        $this->_container      = $_container;
    }

    /**
     * Get info student
     * @param Etudiant $_etudiant
     * @return mixed
     */
    public function getInfoEtudiant(Etudiant $_etudiant)
    {
        $_note = EntityName::NOTE;

        $_dql = "SELECT et.nom, et.niveau, et.annee, mt.libelle, mt.coefficient, nt.note, (nt.note * mt.coefficient) AS ponderee
                 FROM $_note nt
                 JOIN nt.etudiant et
                 JOIN nt.matiere mt
                 WHERE et.id = :etudiant";

        $_query = $this->_entity_manager->createQuery($_dql);
        $_query->setParameter('etudiant', $_etudiant->getId());

        return $_query->getResult();

        /*$_data = [];
        foreach ($_results as $result) {
            $_data['nom'][]         = $result['note']->getEtudiant()->getNom();
            $_data['niveau'][]      = $result['note']->getEtudiant()->getNiveau();
            $_data['annee'][]       = $result['note']->getEtudiant()->getAnnee();
            $_data['libelle'][]     = $result['note']->getMatiere()->getLibelle();
            $_data['coefficient'][] = $result['note']->getMatiere()->getCoefficient();
            $_data['ponderee'][]    = $result['ponderee'];
        }
        $_data['average'][] = $this->getAverageByStudent($_etudiant);

        return $_data;*/
    }

    /**
     * Get average by student
     * @param Etudiant $_etudiant
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAverageByStudent(Etudiant $_etudiant)
    {
        $_note = EntityName::NOTE;

        $_dql = "SELECT AVG(nt.note) AS moyenne
                 FROM $_note nt
                 JOIN nt.etudiant et
                 WHERE et.id = :etudiant";

        $_query = $this->_entity_manager->createQuery($_dql);
        $_query->setParameter('etudiant', $_etudiant->getId());

        return $_query->getOneOrNullResult()['moyenne'];
    }
}