<?php

/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 02/05/2019
 * Time: 10:47
 */

namespace WebEtDesign\MailingBundle\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use WebEtDesign\MailingBundle\Entity\MailingEmailing;

/**
 * @method MailingEmailing|null find($id, $lockMode = null, $lockVersion = null)
 * @method MailingEmailing|null findOneBy(array $criteria, array $orderBy = null)
 * @method MailingEmailing[]    findAll()
 * @method MailingEmailing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailingEmailingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MailingEmailing::class);
    }


}
