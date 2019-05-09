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
use WebEtDesign\MailingBundle\Entity\MailingListContact;

/**
 * @method MailingListContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method MailingListContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method MailingListContact[]    findAll()
 * @method MailingListContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailingListContactRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MailingListContact::class);
    }


}
