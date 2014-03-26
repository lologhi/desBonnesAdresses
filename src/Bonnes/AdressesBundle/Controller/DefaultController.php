<?php

namespace Bonnes\AdressesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
    
    public function indexAction() {
        $products = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findAll();

        if (!$products) {
            throw $this->createNotFoundException('No product found');
        }
        
        //var_dump($product);
        
        return $this->render('BonnesAdressesBundle:Default:index.html.twig', array('products' => $products));
    }
    
    public function nameAction($name = 'Garance') {
        $products = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findOneByName($name);

        if (!$products) {
            throw $this->createNotFoundException('No product found');
        }
        
        //var_dump($product);
        
        return $this->render('BonnesAdressesBundle:Default:index.html.twig', array('products' => $products));
    }
}
