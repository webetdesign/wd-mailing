<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 02/05/2019
 * Time: 17:15
 */

namespace WebEtDesign\MailingBundle\EventListener;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use WebEtDesign\MailingBundle\Entity\MailingEmailing;

use Doctrine\Common\EventSubscriber;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\Dotenv\Dotenv;

use Doctrine\ORM\Events;

class UpdateUser implements EventSubscriber
{

    /**
     * @return array|string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::preRemove,
            Events::preUpdate,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->update($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->update($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args){
        $entity = $args->getObject();

        if (!$entity instanceof User){
            return;
        }

        $em = $args->getObjectManager();

        $mailings = $em->getRepository(MailingEmailing::class)->findBy(["email" => $entity->getEmail()]);

        foreach ($mailings as $mailing) {
            $em->remove($mailing);
        }

        $em->flush();
    }

    /**
     * @param LifecycleEventArgs $args
     */
    private function update(LifecycleEventArgs $args){
        $entity = $args->getObject();

        if (!$entity instanceof User){
            return;
        }

        $em = $args->getObjectManager();

        if ($entity->isEmailing() && !$entity->getMailingEmailing() && !$em->getRepository(MailingEmailing::class)->findBy(["email" => $entity->getEmail()])){

            $mailing_user = new MailingEmailing();
            $mailing_user->setUser($entity);
            $mailing_user->setName($entity->getUsername());
            $mailing_user->setEmail($entity->getEmail());

            $em->persist($mailing_user);
            $em->flush();

            $entity->setMailingEmailing($mailing_user);

            $em->flush();

        }elseif (!$entity->isEmailing() && $entity->getMailingEmailing()){
            $mailings = $em->getRepository(MailingEmailing::class)->findBy(["email" => $entity->getEmail()]);

            foreach ($mailings as $mailing) {
                $em->remove($mailing);
            }

            $entity->setMailingEmailing(null);

            $em->flush();
        }
    }


}
