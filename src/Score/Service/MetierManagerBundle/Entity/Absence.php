<?php

namespace App\Score\Service\MetierManagerBundle\Entity;

use App\Score\Service\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Absence
 *
 * @ORM\Table(name="absence")
 * @ORM\Entity
 */
class Absence
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
     * @var string
     *
     * @ORM\Column(name="motif", type="text", nullable=false)
     */
    private $motif;

    /**
     * @var string
     *
     * @ORM\Column(name="pj", type="string", length=255, nullable=true)
     */
    private $pj;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime", nullable=true)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime", nullable=true)
     */
    private $dateFin;

    /**
     * @var AbsenceType
     *
     * @ORM\ManyToOne(targetEntity="App\Score\Service\MetierManagerBundle\Entity\AbsenceType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="absence_type_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $absenceType;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Score\Service\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $user;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * @param string $motif
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;
    }

    /**
     * @return string
     */
    public function getPj()
    {
        return $this->pj;
    }

    /**
     * @param string $pj
     */
    public function setPj($pj)
    {
        $this->pj = $pj;
    }

    /**
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param \DateTime $dateDebut
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param \DateTime $dateFin
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return AbsenceType
     */
    public function getAbsenceType()
    {
        return $this->absenceType;
    }

    /**
     * @param AbsenceType $absenceType
     */
    public function setAbsenceType($absenceType)
    {
        $this->absenceType = $absenceType;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}