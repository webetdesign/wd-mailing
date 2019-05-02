<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 02/05/2019
 * Time: 10:47
 */

namespace WebEtDesign\MailingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="WebEtDesign\MailingBundle\Repository\MailingEmailingRepository")
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
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string
     */
    private $email;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }


}
