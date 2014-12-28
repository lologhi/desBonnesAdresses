<?php

namespace Bonnes\AdressesBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class AdresseAdmin extends Admin {

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper) {
        $formMapper->with('Général', array('class' => 'col-md-6'))
            ->add('name')
            ->add('adresse')
            ->add('codePostal')
            ->add('ville')
            ->add('telephone')
            ->add('url')
			->end()
        ;

        $formMapper->with('Blabla', array('class' => 'col-md-6'))
            ->add('latitude')
            ->add('longitude')
            ->add('marker')
            ->add('type', null, array('required' => false, 'by_reference' => false))
            ->add('origine', null, array('required' => false))
            ->add('description', 'textarea', array('required' => false))
            ->add('prix', null, array('required' => false))
			->end()
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
            ->add('telephone')
        ;
    }
}
