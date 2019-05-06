<?php
/**
 * Created by PhpStorm.
 * User: jvaldena
 * Date: 22/01/2019
 * Time: 15:34
 */

namespace WebEtDesign\MailingBundle\DependencyInjection;


use Doctrine\ORM\Mapping\ClassMetadata;
use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use WebEtDesign\CmsBundle\Entity\AbstractCmsRoute;
use WebEtDesign\CmsBundle\Entity\CmsContent;
use WebEtDesign\CmsBundle\Entity\CmsContentSlider;
use WebEtDesign\CmsBundle\Entity\CmsRoute;
use WebEtDesign\MailingBundle\Entity\MailingEmailing;

class WebEtDesignMailingExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processor     = new Processor();
        $config        = $processor->processConfiguration($configuration, $configs);

        $this->configureClass($config, $container);

        $this->registerDoctrineMapping($config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');


    }

    /**
     * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function configureClass($config, ContainerBuilder $container)
    {
        // manager configuration
        $container->setParameter('wd_mailing.admin.content.user', $config['class']['user']);
        $container->setParameter('wd_mailing.admin.content.media', $config['class']['media']);
    }

    private function registerDoctrineMapping($config)
    {
        $collector = DoctrineCollector::getInstance();

        $collector->addAssociation(MailingEmailing::class, 'mapOneToOne', [
            'fieldName'     => 'user',
            'targetEntity'  => $config['class']['user'],
            'cascade'       => [
            ],
            'mappedBy'      => null,
            'inversedBy'    => "mailingEmailing",
            'joinColumns'   => [
                [
                    'name'                 => 'user_id',
                    'referencedColumnName' => 'id',
                ],
            ],
            'orphanRemoval' => false,
        ]);


    }


    public function getAlias()
    {
        return 'web_et_design_mailing';
    }
}
