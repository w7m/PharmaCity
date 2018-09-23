<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * Prescription
 *
 * @ORM\Table(name="prescription")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PrescriptionRepository")
 */
class Prescription
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
     * @var int
     *
     * @ORM\Column(name="reference", type="string", unique=true)
     */
    private $reference;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdd;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=80)
     */
    private $status;


    /**
     * Get id
     *
     * @return int
     */


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Patient",inversedBy="prescription")
     * @MaxDepth(2)
     */

    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Pharmacy",inversedBy="prescription")
     * @MaxDepth(2)
     */

    private $pharmacy;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Doctor",inversedBy="prescription")
     * @MaxDepth(2)
     */
    private $doctor;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PrescriptionMedication",mappedBy="prescription",cascade={"persist"})
     *
     */
    private $prescriptionMedication;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set reference
     *
     * @param integer $reference
     *
     * @return Prescription
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return int
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     *
     * @return Prescription
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Prescription
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set patient
     *
     * @param \AppBundle\Entity\Patient $patient
     *
     * @return Prescription
     */
    public function setPatient(\AppBundle\Entity\Patient $patient = null)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return \AppBundle\Entity\Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * Set pharmacy
     *
     * @param \AppBundle\Entity\Pharmacy $pharmacy
     *
     * @return Prescription
     */
    public function setPharmacy(\AppBundle\Entity\Pharmacy $pharmacy = null)
    {
        $this->pharmacy = $pharmacy;

        return $this;
    }

    /**
     * Get pharmacy
     *
     * @return \AppBundle\Entity\Pharmacy
     */
    public function getPharmacy()
    {
        return $this->pharmacy;
    }

    /**
     * Set doctor
     *
     * @param \AppBundle\Entity\Doctor $doctor
     *
     * @return Prescription
     */
    public function setDoctor(\AppBundle\Entity\Doctor $doctor = null)
    {
        $this->doctor = $doctor;

        return $this;
    }

    /**
     * Get doctor
     *
     * @return \AppBundle\Entity\Doctor
     */
    public function getDoctor()
    {
        return $this->doctor;
    }
    public function __construct()
    {
      $this->dateAdd = new \DateTime();
      $this->status = "Non confirmé";
      $this->reference = md5(((FLOAT)microtime())*950000);
    }

    /**
     * Add prescriptionMedication
     *
     * @param \AppBundle\Entity\PrescriptionMedication $prescriptionMedication
     *
     * @return Prescription
     */
    public function addPrescriptionMedication(\AppBundle\Entity\PrescriptionMedication $prescriptionMedication)
    {
        $this->prescriptionMedication[] = $prescriptionMedication;

        return $this;
    }

    /**
     * Remove prescriptionMedication
     *
     * @param \AppBundle\Entity\PrescriptionMedication $prescriptionMedication
     */
    public function removePrescriptionMedication(\AppBundle\Entity\PrescriptionMedication $prescriptionMedication)
    {
        $this->prescriptionMedication->removeElement($prescriptionMedication);
    }

    /**
     * Get prescriptionMedication
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrescriptionMedication()
    {
        return $this->prescriptionMedication;
    }
}
