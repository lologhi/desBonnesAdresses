<?php

namespace Bonnes\AdressesBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class AdresseAdmin extends Admin {
    
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper
            ->add('name')
            ->add('adresse')
            ->add('codePostal')
            ->add('ville')
            ->add('url')
            ->add('url')
            ->add('origine')
            ->add('prix')
            ->add('description')
            ->add('longitude')
            ->add('latitude')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
            ->add('name')
            ->add('codePostal')
            ->add('ville')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->addIdentifier('name')
            ->add('adresse')
            ->add('codePostal')
            ->add('ville')
        ;
    }
}