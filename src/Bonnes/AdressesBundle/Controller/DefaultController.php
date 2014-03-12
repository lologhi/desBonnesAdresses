<?php

namespace Bonnes\AdressesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BonnesAdressesBundle:Default:index.html.twig', array('name' => $name));
    }
}
