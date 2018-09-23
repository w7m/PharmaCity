<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\MaxDepth;
/**
 * Supplier
 *
 * @ORM\Table(name="supplier")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SupplierRepository")
 */
class Supplier
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
     * @ORM\Column(name="name_society", type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Entrez le nom de votre societé .")
     * @Assert\Length(
     *     min=3,
     *     max=150,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long."
     * )
     */
    private $nameSociety;

    /**
     * @var int
     *
     * @ORM\Column(name="bank_account_numbers", type="integer", unique=true)
     * @Assert\Type(
     *     type="numeric",
     *     message="Poids doit etre de type numeric"
     * )
     */
    private $bankAccountNumbers;


    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User",cascade={"persist","remove"})
     * @Assert\Valid
     * @MaxDepth(2)
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
     * Set nameSociety
     *
     * @param string $nameSociety
     *
     * @return Supplier
     */
    public function setNameSociety($nameSociety)
    {
        $this->nameSociety = $nameSociety;

        return $this;
    }

    /**
     * Get nameSociety
     *
     * @return string
     */
    public function getNameSociety()
    {
        return $this->nameSociety;
    }

    /**
     * Set bankAccountNumbers
     *
     * @param integer $bankAccountNumbers
     *
     * @return Supplier
     */
    public function setBankAccountNumbers($bankAccountNumbers)
    {
        $this->bankAccountNumbers = $bankAccountNumbers;

        return $this;
    }

    /**
     * Get bankAccountNumbers
     *
     * @return int
     */
    public function getBankAccountNumbers()
    {
        return $this->bankAccountNumbers;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Supplier
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
