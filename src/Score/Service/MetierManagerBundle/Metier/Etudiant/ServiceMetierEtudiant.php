<?php

namespace App\Score\Service\MetierManagerBundle\Metier\Etudiant;

use App\Score\Service\MetierManagerBundle\Entity\Etudiant;
use App\Score\Service\MetierManagerBundle\Entity\Niveau;
use App\Score\Service\MetierManagerBundle\Utils\EntityName;
use App\Score\Service\MetierManagerBundle\Utils\ServiceName;
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
    private $_web_root;

    /**
     * ServiceMetierEtudiant constructor.
     * @param EntityManager $_entity_manager
     * @param Container $_container
     * @param $_root_dir
     */
    public function __construct(EntityManager $_entity_manager, Container $_container, $_root_dir)
    {
        $this->_entity_manager = $_entity_manager;
        $this->_container      = $_container;
        $this->_web_root       = realpath($_root_dir . '/../public');
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

    /**
     * @param $_start
     * @param $_length
     * @param $_search
     * @param $_niveau
     * @param $_order_by
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getListStudents($_start, $_length, $_search, $_niveau, $_order_by)
    {
        $_order_by  = $_order_by ? $_order_by : "std.id DESC";
        $_student   = EntityName::ETUDIANT;
        $_and_where = "";

        if ($_niveau > 0)
            $_and_where = "AND std.niveau = $_niveau";

        $_dql = "SELECT std.nom, std.adresse, std.id
                 FROM $_student std
                 WHERE (std.nom LIKE :search
                        OR std.adresse LIKE :search)
                 $_and_where
                 ORDER BY $_order_by";

        $_query = $this->_entity_manager->createQuery($_dql);
        $_query->setParameter(':search', '%' . $_search['value'] . '%')
            ->setFirstResult($_start)
            ->setMaxResults($_length);

        $_results = $_query->getResult();

        return ['results' => $_results, 'countResult' => $this->getCountStudent()];
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCountStudent()
    {
        $_student = EntityName::ETUDIANT;

        $_dql = "SELECT COUNT(std) AS nbTotal
                 FROM $_student std";

        $_query   = $this->_entity_manager->createQuery($_dql);
        $_results = $_query->getOneOrNullResult();

        return $_results['nbTotal'] ? $_results['nbTotal'] : 0;
    }

    /**
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function importStudent()
    {
        $_source_path = $this->_container->getParameter('external_location_path');
        $_file_name   = $this->_web_root . $_source_path;

        if ($_file_name) {
            $_reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $_reader->setReadDataOnly(true);
            $_spread_sheet = $_reader->load($_file_name);
            $_sheet_count  = $_spread_sheet->getSheetCount();
            if ($_sheet_count > 0) {
                for ($_i = 0; $_i < $_sheet_count; $_i++) {
                    $_active_sheet = $_spread_sheet->getSheet($_i);
                    $_sheet        = $_active_sheet->toArray();
                    $_data_body    = array_filter($_sheet);
                    array_shift($_data_body);
                    $_sheet_name = ucfirst($_spread_sheet->getSheetNames()[$_i]);
                    $_level      = $this->findOrCreateLevelByName($_sheet_name);

                    $this->UpdateOrCreateStudentByLevel($_data_body, $_level);
                }
            }
            return true;
        }

        return false;
    }

    /**
     * @param $_label
     * @return Niveau|array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function findOrCreateLevelByName($_label)
    {
        // Get manager
        $_utils_manager = $this->_container->get(ServiceName::SRV_METIER_UTILS);

        $_level = $_utils_manager->findOneEntityByFilter(EntityName::NIVEAU, ['libelle' => $_label]);
        if (!$_level) {
            $_level = new Niveau();
            $_level->setLibelle($_label);
            $this->_entity_manager->persist($_level);
            $this->_entity_manager->flush();
        }

        return $_level;
    }

    /**
     * @param $_data
     * @param $_level
     * @throws \Exception
     */
    public function UpdateOrCreateStudentByLevel($_data, $_level)
    {
        foreach ($_data as $_student) {
            // Get manager
            $_utils_manager   = $this->_container->get(ServiceName::SRV_METIER_UTILS);
            $_action          = 'new';
            $_student_exist   = $_utils_manager->findOneEntityByFilter(EntityName::ETUDIANT, [
                'nom'    => $_student[0],
                'niveau' => $_level
            ]);
            $_student_to_save = $_student_exist ? $_student_exist : new Etudiant();
            $_student_to_save->setNom($_student[0]);
            $_student_to_save->setEmail($_student[1]);
            $_student_to_save->setAdresse($_student[2]);
            $_student_to_save->setSexe($_student[3]);
            $_student_to_save->setAnnee(new \DateTime(date('Y',strtotime($_student[4]))));
            $_student_to_save->setNiveau($_level);
            if ($_student_exist)
                $_action = 'update';

            $_utils_manager->saveEntity($_student_to_save, $_action);
        }
    }
}
