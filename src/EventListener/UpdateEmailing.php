<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 02/05/2019
 * Time: 17:15
 */

namespace WebEtDesign\MailingBundle\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use WebEtDesign\MailingBundle\Entity\MailingEmailing;

use Doctrine\Common\EventSubscriber;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\Dotenv\Dotenv;

class UpdateEmailing implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
        ];
    }


    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof MailingEmailing){
            return;
        }

        $listID = $this->getListId();

        try{
            $dotenv = new Dotenv();
            $dotenv->load(realpath("./../").'/.env');

            $public_key = $_ENV['MAILJET_PUBLIC_API_KEY'];
            $private_key = $_ENV['MAILJET_PRIVATE_API_KEY'];
        }catch (\Exception $e){
            return;
        }

        $mj = new Client($public_key, $private_key);


    }

    public function getListId(){
        return "2072394";
    }


}
