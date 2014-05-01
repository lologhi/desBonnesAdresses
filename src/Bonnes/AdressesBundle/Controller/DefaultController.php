<?php

namespace Bonnes\AdressesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Gedmo\Sluggable\Util as Sluggable;

class DefaultController extends Controller {

    public function indexAction() {
        $addresses = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findAll();
        if (!$addresses) { throw $this->createNotFoundException('No addresses found'); }

        return $this->render('BonnesAdressesBundle:Default:index.html.twig', array('addresses' => $addresses));
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

    public function detailsAction($name) {
        $addresses = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findAll();
        if (!$addresses) { throw $this->createNotFoundException('No addresses found'); }
        $address = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findOneBySlug($name);
        if (!$address) { throw $this->createNotFoundException('No address found'); }

        return $this->render('BonnesAdressesBundle:Default:index.html.twig', array('addresses' => $addresses, 'specificAddress' => $address));
    }

    public function slugfeederAction() {
        $em = $this->get('doctrine_mongodb');
        $addresses = $em->getRepository('BonnesAdressesBundle:Adresse')->findAll();
        if (!$addresses) { throw $this->createNotFoundException('No addresses found'); }

        foreach($addresses as $address) {
            if ($address->getSlug() == '') {
                $slug = Sluggable\Urlizer::urlize($address->getName(), '-');
                $address->setSlug($slug);
                $dm = $this->get('doctrine_mongodb')->getManager();
                $dm->persist($address);
                $dm->flush();
            }
        }

        return $this->render('BonnesAdressesBundle:Default:index.html.twig', array('addresses' => $addresses));
    }
}
