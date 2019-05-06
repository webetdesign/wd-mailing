<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 02/05/2019
 * Time: 10:47
 */

namespace WebEtDesign\MailingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="WebEtDesign\MailingBundle\Repository\MailingEmailingRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("idEmailing")
 */
class MailingEmailing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var integer
     */
    private $idMailjet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     * @Assert\Email()
     * @var string
     */
    private $email;

    /*
     * @ORM\OneToOne(targetEntity="App\Application\Sonata\UserBundle\Entity\User", inversedBy="mailingEmailing", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return int
     */
    public function getIdMailjet(): int
    {
        return $this->idMailjet;
    }

    /**
     * @param int $idMailjet
     */
    public function setIdMailjet(int $idMailjet)
    {
        $this->idMailjet = $idMailjet;
    }



    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }








}
