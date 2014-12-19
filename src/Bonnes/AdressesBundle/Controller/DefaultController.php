<?php

namespace Bonnes\AdressesBundle\Controller;

use Bonnes\AdressesBundle\Model\Geometry;
use Bonnes\AdressesBundle\Model\Point;
use Bonnes\AdressesBundle\Model\Properties;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Gedmo\Sluggable\Util as Sluggable;

class DefaultController extends Controller {

    public function indexAction() {
        $filename = 'lastmodification.txt';
		if (file_exists($filename)) { $lastmodification = new \DateTime(file_get_contents($filename)); }

        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $addresses = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findAll();
        if (!$addresses) { throw $this->createNotFoundException('No addresses found'); }

        // http://www.testically.org/2011/08/25/using-a-unique-index-in-mongodb-with-doctrine-odm-and-symfony2/
        //$dm = $this->get('doctrine_mongodb')->getManager();
        //$dm->getSchemaManager()->ensureIndexes();

        $points = array();
        foreach ($addresses as $addresse) {
            $properties = new Properties();
            $properties->setName($addresse->getName());
            $properties->setSlug($addresse->getSlug());
            $properties->setMarkerSymbol($addresse->getMarker());
            $properties->setMarkerSize('small');
            $properties->setAdresse($addresse->getAdresseComplete());
            $properties->setUrl($addresse->getUrl());
            $properties->setOrigine($addresse->getOrigine());
            $properties->setPrix($addresse->getPrix());
            $properties->setDescription($addresse->getDescription());
            $properties->setTelephone($addresse->getTelephone());

            $geometry = new Geometry();
            $geometry->setType('Point');
            $geometry->setCoordinates(array($addresse->getLongitude(), $addresse->getLatitude()));

            $point = new Point();
            $point->setType('Feature');
            $point->setGeometry($geometry);
            $point->setProperties($properties);
            $points[] = $point;
        }

        return $this->render('BonnesAdressesBundle:Default:index.html.twig', array('points' => $serializer->serialize($points, 'json'), 'lastmodification' => $lastmodification));
    }

    public function addressAction(Request $request) {
        $id = $request->request->get('idAddress');
        if (!$id) { throw $this->createNotFoundException('No id found'); }

        $address = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->find($id);
        if (!$address) { throw $this->createNotFoundException('No address found'); }

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array(new JsonEncoder()));

        return new Response($serializer->serialize($address, 'json'));
    }

    public function detailsAction($slug) {
        $addresses = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findAll();
        if (!$addresses) { throw $this->createNotFoundException('No addresses found'); }
        $address = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findOneBySlug($slug);
        if (!$address) { throw $this->createNotFoundException('No address found'); }

        return $this->render('BonnesAdressesBundle:Default:index.html.twig', array('addresses' => $addresses, 'specificAddress' => $address, 'specificAddressComplete' => $address->getAdresseComplete()));
    }

    public function findByNameAction($search) {
        $addresses = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findByName($search);

    }

    public function slugfeederAction() {
        $em = $this->get('doctrine_mongodb');
        $addresses = $em->getRepository('BonnesAdressesBundle:Adresse')->findAll();
        if (!$addresses) { throw $this->createNotFoundException('No addresses found'); }

        foreach($addresses as $address) {
            if ($address->getSlug() == '') {
                $slug = Sluggable\Urlizer::urlize($address->getName(), '-');
                $address->setSlug($slug);
                $em->getManager()->persist($address);
            }
        }
        
        $em->getManager()->flush();

        return $this->render('BonnesAdressesBundle:Default:index.html.twig', array('addresses' => $addresses));
    }
}
