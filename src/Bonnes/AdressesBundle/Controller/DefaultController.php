<?php

namespace Bonnes\AdressesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
    
    public function indexAction($name) {
        $product = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findAll();

        if (!$product) {
            throw $this->createNotFoundException('No product found for id '.$name);
        }
        
        var_dump($product);
        
        return $this->render('BonnesAdressesBundle:Default:index.html.twig', array('name' => $product));
    }
}
