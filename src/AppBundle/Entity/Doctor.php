<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;
/**
 * Doctor
 *
 * @ORM\Table(name="doctor")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DoctorRepository")
 */
class Doctor
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
     * @ORM\Column(name="specialty", type="string", length=100)
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     * )
     */
    private $specialty;

    /**
     * @var \DateTime
     * @return \DateTime
     * @ORM\Column(name="graduationDate", type="datetime")
     * @Assert\NotBlank(message="Veuillez remplir ce champs")
     * @Assert\Type(type="\DateTime",message="Veuillez entrer une date valid")
     */
    private $graduationDate;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User",cascade={"persist","remove"})
     * @Assert\Valid
     * @MaxDepth(2)
     */
    private $user;




    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Prescription",mappedBy="doctor")
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
     * Set specialty
     *
     * @param string $specialty
     *
     * @return Doctor
     */
    public function setSpecialty($specialty)
    {
        $this->specialty = $specialty;

        return $this;
    }

    /**
     * Get specialty
     *
     * @return string
     */
    public function getSpecialty()
    {
        return $this->specialty;
    }

    /**
     * Set graduationDate
     *
     * @param \DateTime $graduationDate
     *
     * @return Doctor
     */
    public function setGraduationDate($graduationDate)
    {
        $this->graduationDate = $graduationDate;

        return $this;
    }

    /**
     * Get graduationDate
     *
     * @return \DateTime
     */
    public function getGraduationDate()
    {
        return $this->graduationDate;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Doctor
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
     * @return Doctor
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
