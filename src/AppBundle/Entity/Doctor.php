<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
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
     */
    private $specialty;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="graduationDate", type="date")
     */
    private $graduationDate;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User",cascade={"persist","remove"})
     * @Assert\Valid
     */
    private $user;


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
}
