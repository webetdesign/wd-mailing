<?php

namespace WebEtDesign\MailingBundle\Controller\MailJet;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\Dotenv\Dotenv;
use WebEtDesign\MailingBundle\Entity\MailingEmailing;

class MailJetController extends Controller
{

    public function getCampaignAction(Request $request)
    {
        $datas = [];

        try{
            $dotenv = new Dotenv();
            $dotenv->load(realpath("./../").'/.env');

            $public_key = $_ENV['MAILJET_PUBLIC_API_KEY'];
            $private_key = $_ENV['MAILJET_PRIVATE_API_KEY'];

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
