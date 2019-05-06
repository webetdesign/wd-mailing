<?php

declare(strict_types=1);

namespace WebEtDesign\MailingBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class MailingEmailingAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('idMailjet')
            ->add('name')
            ->add('email')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        unset($this->listModes['mosaic']);

        $listMapper
            ->add('id')
            ->add('idMailjet', "text")
            ->add('name')
            ->add('email')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('id')
            ->add('idMailjet')
            ->add('name')
            ->add('email')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('idMailjet')
            ->add('name')
            ->add('email')
            ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove("create");
    }
}
