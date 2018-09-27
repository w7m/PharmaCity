<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Media
 *
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MediaRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Media
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
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;
    /**
     * @var string
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;
    /**
     *
     * @Assert\File(
     *     maxSize = "1024k",
     *     maxSizeMessage="s'il vous plaît upload un image de taille max : 1M.",
     *     mimeTypesMessage = "l'extension de l'image doit de type jpg."
     * )
     */
    private $file;

    private $fileName;


    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User",inversedBy="media")
     */
    private $user;

    public function getId()
    {
        return $this->id;
    }
    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Media
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }
    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Media
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }
    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }
    /**
     * @param mixed $file
     */
    public function setFile($file = null)
    {
        $this->file = $file;
    }



    /**
     * @ORM\PrePersist()
     */
    public function hydrateObject()
    {
        if (file_exists($this->file)){
        $this->fileName = md5(uniqid()).".".$this->file->guessExtension();
        $this->setPath("upload/picture_user/".$this->fileName);
        $this->setAlt("user");
        } else {

            $this->setPath("upload/picture_user/default_picture.png");
            $this->setAlt("user");
        }
    }


    /**
     * @ORM\PostPersist()
     */
    public function uploadFile()
    {
        if (file_exists($this->file)){
        $this->file->move($GLOBALS['kernel']->getContainer()->getParameter('user_media'),$this->fileName);
        } else {
            return;
        }
    }


    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Media
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
