<?php

namespace App\Score\Service\MetierManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Note
 *
 * @ORM\Table(name="note")
 * @ORM\Entity
 */
class Note
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="note", type="float", nullable=false)
     */
    private $note;

    /**
     * @var Etudiant
     *
     * @ORM\ManyToOne(targetEntity="App\Score\Service\MetierManagerBundle\Entity\Etudiant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="etudiant_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $etudiant;

    /**
     * @var Matiere
     *
     * @ORM\ManyToOne(targetEntity="App\Score\Service\MetierManagerBundle\Entity\Matiere")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="matiere_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $matiere;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param float $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return Etudiant
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }

    /**
     * @param Etudiant $etudiant
     */
    public function setEtudiant($etudiant)
    {
        $this->etudiant = $etudiant;
    }

    /**
     * @return Matiere
     */
    public function getMatiere()
    {
        return $this->matiere;
    }

    /**
     * @param Matiere $matiere
     */
    public function setMatiere($matiere)
    {
        $this->matiere = $matiere;
    }
}