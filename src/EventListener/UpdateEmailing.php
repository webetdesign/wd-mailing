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

use Doctrine\ORM\Events;

class UpdateEmailing implements EventSubscriber
{
    /**
     * @return array|string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preRemove,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof MailingEmailing){
            return;
        }

        $listID = $this->getListId();

        $mj = $this->getClient();

        $res = $mj->post(Resources::$ContactslistManagecontact, [
            "body" => [
                "Email" => $entity->getEmail(),
                "Action" => "addforce",
                "Name" => $entity->getName() ? $entity->getName() : substr($entity->getEmail(), 0, strpos($entity->getEmail(), "@") )
            ],
            "ID" => $listID,
        ]);


        $entity->setIdEmailing($res->getData()[0]["ContactID"]);

        $res_contact = $mj->put(Resources::$Contact, [
            "ID" => $entity->getIdEmailing(),
            "body" => [
                "IsExcludedFromCampaigns" => 0
            ]
        ]);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args){
        $entity = $args->getObject();

        if (!$entity instanceof MailingEmailing){
            return;
        }

        $listID = $this->getListId();

        $mj = $this->getClient();


        $res_contactdata = $mj->delete(Resources::$Contactdata, [
            "ID" => $entity->getIdEmailing()
        ]);

        $res_contact = $mj->put(Resources::$Contact, [
            "ID" => $entity->getIdEmailing(),
            "body" => [
                "IsExcludedFromCampaigns" => 1
            ]
        ]);

    }

    /**
     * @return Client|void
     */
    public function getClient(){
        try{
            $dotenv = new Dotenv();
            $dotenv->load(realpath("./../").'/.env');

            $public_key = $_ENV['MAILJET_PUBLIC_API_KEY'];
            $private_key = $_ENV['MAILJET_PRIVATE_API_KEY'];
        }catch (\Exception $e){
            dump($e);
            return;
        }

        return new Client($public_key, $private_key);
    }

    /**
     * @return int
     */
    public function getListId(){
        return 2072394;
    }


}
