<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * PrescriptionMedication
 *
 * @ORM\Table(name="prescription_medication")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PrescriptionMedicationRepository")
 */
class PrescriptionMedication
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
     * @ORM\Column(name="Price", type="string", length=255, nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="Quantity", type="string", length=255, nullable=true)
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Medication")
     * @MaxDepth(2)
     */
    private $medication;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Prescription",inversedBy="prescriptionMedication")
     * @MaxDepth(2)
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
     * Set price
     *
     * @param string $price
     *
     * @return PrescriptionMedication
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set quantity
     *
     * @param string $quantity
     *
     * @return PrescriptionMedication
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set medication
     *
     * @param \AppBundle\Entity\Medication $medication
     *
     * @return PrescriptionMedication
     */
    public function setMedication(\AppBundle\Entity\Medication $medication = null)
    {
        $this->medication = $medication;

        return $this;
    }

    /**
     * Get medication
     *
     * @return \AppBundle\Entity\Medication
     */
    public function getMedication()
    {
        return $this->medication;
    }

    /**
     * Set prescription
     *
     * @param \AppBundle\Entity\Prescription $prescription
     *
     * @return PrescriptionMedication
     */
    public function setPrescription(\AppBundle\Entity\Prescription $prescription = null)
    {
        $this->prescription = $prescription;

        return $this;
    }

    /**
     * Get prescription
     *
     * @return \AppBundle\Entity\Prescription
     */
    public function getPrescription()
    {
        return $this->prescription;
    }

    public function __toString()
    {
        return $this->prescription;
    }
}
