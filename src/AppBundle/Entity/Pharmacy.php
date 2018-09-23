<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * Pharmacy
 *
 * @ORM\Table(name="pharmacy")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PharmacyRepository")
 */
class Pharmacy
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name_pharmacy", type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Entrez le nom de votre pharmacy .")
     * @Assert\Length(
     *     min=3,
     *     max=150,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long."
     * )
     */
    private $namePharmacy;

    /**
     * @var string
     * @Assert\NotBlank(message="Entrez si vous êtes pharmacy jour ou nuit .")
     * @ORM\Column(name="opening_time", type="string", length=50)
     */
    private $openingTime;


    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User",cascade={"persist","remove"})
     * @Assert\Valid
     * @MaxDepth(2)
     */
    private $user;



    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Prescription",mappedBy="pharmacy")
     *
     */
    private $prescription;

    /**
     * Get id
     *
     * @return int
     */



    public function getId()
    {
        return $this->id;
    }

    /**
     * Set namePharmacy
     *
     * @param string $namePharmacy
     *
     * @return Pharmacy
     */
    public function setNamePharmacy($namePharmacy)
    {
        $this->namePharmacy = $namePharmacy;

        return $this;
    }

    /**
     * Get namePharmacy
     *
     * @return string
     */
    public function getNamePharmacy()
    {
        return $this->namePharmacy;
    }

    /**
     * Set openingTime
     *
     * @param string $openingTime
     *
     * @return Pharmacy
     */
    public function setOpeningTime($openingTime)
    {
        $this->openingTime = $openingTime;

        return $this;
    }

    /**
     * Get openingTime
     *
     * @return string
     */
    public function getOpeningTime()
    {
        return $this->openingTime;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Pharmacy
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prescription = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add prescription
     *
     * @param \AppBundle\Entity\Prescription $prescription
     *
     * @return Pharmacy
     */
    public function addPrescription(\AppBundle\Entity\Prescription $prescription)
    {
        $this->prescription[] = $prescription;

        return $this;
    }

    /**
     * Remove prescription
     *
     * @param \AppBundle\Entity\Prescription $prescription
     */
    public function removePrescription(\AppBundle\Entity\Prescription $prescription)
    {
        $this->prescription->removeElement($prescription);
    }

    /**
     * Get prescription
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrescription()
    {
        return $this->prescription;
    }
}
