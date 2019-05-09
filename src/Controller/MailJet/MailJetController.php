<?php

namespace WebEtDesign\MailingBundle\Controller\MailJet;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\Dotenv\Dotenv;
use WebEtDesign\MailingBundle\Entity\MailingListContact;

class MailJetController extends Controller
{
    public function updateListAction(Request $request){

        $id = $request->request->get('id');

        $em = $this->container->get('doctrine.orm.entity_manager');

        $public_key = $this->getParameter('wd_mailing.mailjet.public_key');
        $private_key = $this->getParameter('wd_mailing.mailjet.secret_key');

        $mj = new Client($public_key, $private_key);

        $res = $mj->get(Resources::$Contactslist, [
            "ID" => $id
        ])->getData();

        try{
            if ($res["ErrorMessage"]){
                return new JsonResponse($res);
            }
        }catch (\Exception $e){

        }

        $list = $em->getRepository(MailingListContact::class)->findAll()[0];

        $list->setIdList($res[0]["ID"]);
        $list->setName($res[0]["Name"]);

        $em->flush();

        return new JsonResponse(json_encode([
            "success" => "La liste a été modifiée"
        ]));

    }
    public function getCampaignAction(Request $request)
    {
        $datas = [];

        try{
            $public_key = $this->getParameter('wd_mailing.mailjet.public_key');
            $private_key = $this->getParameter('wd_mailing.mailjet.secret_key');

            $mj = new Client($public_key, $private_key);

            $id = $request->request->get('id');

            $filters = [
                'SourceId' => $id,
                'CounterSource' => 'Campaign',
                'CounterTiming' => 'Message',
                'CounterResolution' => 'Lifetime'
            ];
            $stats = $mj->get(Resources::$Statcounters, ['filters' => $filters]);

            $campaign = $mj->get(Resources::$Campaign, [
                "ID" => $id
            ]);

            $list = $mj->get(Resources::$Contactslist, [
                "ID" => $campaign->getData()[0]["ListID"]
            ]);

            array_push($datas, $campaign->getData());
            array_push($datas, $stats->getData());
            array_push($datas, $list->getData());

        }catch (\Exception $e){
            $datas = $e->getMessage();
        }

        return new Response(json_encode($datas));
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function unsubAction(Request $request, LoggerInterface $logger, EntityManagerInterface $em)
    {
        $datas = json_decode($request->getContent());


        $logger->error($datas->email);
        $logger->error($datas->event);

        $email = $datas->email;

        if ($email && $datas->event == "unsub"){
            $user = $em->getRepository(User::class)->findOneBy([
                "email" => $email
            ]);

            $user->setEmailing(0);
            $em->flush();
        }

        return new Response("ok");
    }

}
