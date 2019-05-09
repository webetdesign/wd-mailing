<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 16/04/2019
 * Time: 16:38
 */

namespace WebEtDesign\MailingBundle\Block\MailJet;

use Doctrine\ORM\EntityManagerInterface;
use Sonata\BlockBundle\Block\AbstractBlockService;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\Dotenv\Dotenv;
use WebEtDesign\MailingBundle\Entity\MailingEmailing;
use WebEtDesign\MailingBundle\Entity\MailingListContact;

class Config extends AbstractBlockService
{
    private $public_key;
    private $private_key;
    private $params;
    private $em;

    /**
     * @param string $name
     * @param EngineInterface $templating
     */
    public function __construct($name, EngineInterface $templating, EntityManagerInterface $em, string $public_key, string $private_key)
    {
        parent::__construct($name, $templating);
        $this->public_key = $public_key;
        $this->private_key = $private_key;
        $this->em = $em;


    }

    /**
     * @param BlockContextInterface $blockContext
     * @param Response|null $response
     * @return mixed
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {

        $settings = $blockContext->getSettings();

        $template = $settings['template'];

        $mj = new Client($this->public_key, $this->private_key);

        $res = $this->em->getRepository(MailingListContact::class)->findAll();
        if ($res){
            $listUse = $res[0];
        }else{
            $listUse = null;
        }

        $lists = $mj->get(Resources::$Contactslist, []);

        return $this->renderPrivateResponse($template, [
            'lists' =>  $lists->getData(),
            "listUse" => $listUse
        ], $response);
    }

    public function getName()
    {
        return 'Admin MailJet Config';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => "@WebEtDesignMailingBundle/Resources/views/block/mailJet/config.html.twig",

        ]);

        $resolver->setAllowedTypes('template', ['string', 'boolean']);

    }

}
