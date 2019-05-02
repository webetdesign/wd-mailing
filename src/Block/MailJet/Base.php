<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 16/04/2019
 * Time: 16:38
 */

namespace WebEtDesign\MailingBundle\Block\MailJet;

use Sonata\BlockBundle\Block\AbstractBlockService;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Mailjet\Client;
use Mailjet\Resources;
use Symfony\Component\Dotenv\Dotenv;

class Base extends AbstractBlockService
{
    private $public_key;
    private $private_key;
    private $params;

    /**
     * @param string $name
     * @param EngineInterface $templating
     */
    public function __construct($name, EngineInterface $templating)
    {
        parent::__construct($name, $templating);
        $this->public_key = null;
        $this->private_key = null;

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
        $this->updateSettings();

        $mj = new Client($this->public_key, $this->private_key);

        $campaigns = $mj->get(Resources::$Campaign, [
            "filters" => [
                "isDeleted" => 0
            ]
        ]);

        return $this->renderPrivateResponse($template, [
            'campaigns' =>  $campaigns->getData(),
        ], $response);
    }

    public function getName()
    {
        return 'Admin MailJet';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => "@WebEtDesignMailingBundle/Resources/views/block/mailJet/index.html.twig",

        ]);

        $resolver->setAllowedTypes('template', ['string', 'boolean']);

    }

    public function updateSettings(){

        $dotenv = new Dotenv();
        $dotenv->load(realpath("./../").'/.env');

        $this->public_key = $_ENV['MAILJET_PUBLIC_API_KEY'];
        $this->private_key = $_ENV['MAILJET_PRIVATE_API_KEY'];
    }
}
