<?php

namespace Bonnes\AdressesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class DefaultController extends Controller {

    public function indexAction() {
        $products = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findAll();

        if (!$products) {
            throw $this->createNotFoundException('No product found');
        }

        //var_dump($product);

        return $this->render('BonnesAdressesBundle:Default:index.html.twig', array('products' => $products));
    }

    public function addressAction(Request $request) {
        $id = $request->request->get('idAddress');
        if (!$id) { throw $this->createNotFoundException('No id found'); }

        $address = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->find($id);
        if (!$address) { throw $this->createNotFoundException('No address found'); }

        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonAddress = $serializer->serialize($address, 'json');

        return new Response($jsonAddress);
    }
}
