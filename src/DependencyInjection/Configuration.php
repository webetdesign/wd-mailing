<?php
/**
 * Created by PhpStorm.
 * User: jvaldena
 * Date: 22/01/2019
 * Time: 16:27
 */

namespace WebEtDesign\MailingBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('web_et_design_mailing');

        $rootNode
                ->children()
                    ->arrayNode('class')
                        ->children()
                            ->scalarNode('user')->cannotBeEmpty()->end()
                            ->scalarNode('media')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
                ->children()
                    ->arrayNode('MailJet')
                        ->children()
                            ->scalarNode('PUBLIC_API_KEY')->end()
                            ->scalarNode('SECRET_API_KEY')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
