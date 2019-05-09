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
use Psr\Log\LoggerInterface;
use WebEtDesign\MailingBundle\Entity\MailingEmailing;
use WebEtDesign\MailingBundle\Entity\MailingListContact;

use Doctrine\Common\EventSubscriber;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\Dotenv\Dotenv;

use Doctrine\ORM\Events;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class UpdateEmailing
 * @package WebEtDesign\MailingBundle\EventListener
 */
class UpdateEmailing implements EventSubscriber
{
    private $public_key;
    private $secret_key;

    /**
     * UpdateEmailing constructor.
     */
    public function __construct(string $public_key, string $secret_key)
    {
        $this->public_key = $public_key;
        $this->secret_key = $secret_key;
    }

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
        $em = $args->getObjectManager();

        $list = $this->getList($em);
        $listID = $list->getIdList();

        $mj = $this->getClient();

        $res = $mj->post(Resources::$ContactslistManagecontact, [
            "body" => [
                "Email" => $entity->getEmail(),
                "Action" => "addforce",
                "Name" => $entity->getName() ? $entity->getName() : substr($entity->getEmail(), 0, strpos($entity->getEmail(), "@") )
            ],
            "ID" => $listID,
        ]);


        $entity->setIdMailjet($res->getData()[0]["ContactID"]);

        $res_contact = $mj->put(Resources::$Contact, [
            "ID" => $entity->getIdMailjet(),
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

        $em = $args->getObjectManager();

        $list = $this->getList($em);
        $listID = $list->getIdList();

        $mj = $this->getClient();


        $res_contactdata = $mj->delete(Resources::$Contactdata, [
            "ID" => $entity->getIdMailjet()
        ]);

        $res_contact = $mj->post(Resources::$ContactslistManagecontact, [
            "body" => [
                "Email" => $entity->getEmail(),
                "Action" => "unsub"
            ],
            "ID" => $listID,
        ]);

        $user = $entity->getUser();

        $user->setEmailing(0);
        $user->setMailingEmailing(null);
        $em->flush();
    }

    /**
     * @return Client|void
     */
    public function getClient(){
        $public_key = $this->public_key;
        $secret_key = $this->secret_key;

        return new Client($public_key, $secret_key);
    }

    /**
     * @return int
     */
    public function getList(EntityManagerInterface $em){
        return $em->getRepository(MailingListContact::class)->findAll()[0];
    }


}
