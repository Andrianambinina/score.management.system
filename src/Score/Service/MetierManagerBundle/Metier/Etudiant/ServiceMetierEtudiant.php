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
     * Get score by student
     * @param Etudiant $_etudiant
     * @return mixed
     */
    public function getScoreByStudent(Etudiant $_etudiant)
    {
        $_note = EntityName::NOTE;

        $_dql = "SELECT nt
                 FROM $_note nt
                 JOIN nt.etudiant et
                 WHERE et.id = :etudiant";

        $_query = $this->_entity_manager->createQuery($_dql);
        $_query->setParameter('etudiant', $_etudiant->getId());

        $_results = $_query->getResult();
        $_data = [];
        foreach ($_results as $_note) {
            array_push($_data, [
                'designation' => $_note->getMatiere()->getLibelle(),
                'coefficient' => $_note->getMatiere()->getCoefficient(),
                'note'        => $_note->getNote(),
                'ponderee'    => ($_note->getMatiere()->getCoefficient() * $_note->getNote())
            ]);
        }

        return $_data;
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