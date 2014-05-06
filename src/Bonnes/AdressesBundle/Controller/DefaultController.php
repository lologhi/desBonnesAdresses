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

    private $encoders;
    private $normalizers;
    private $serializer;

    public function __construct() {
        $this->encoders = array(new JsonEncoder());
        $this->normalizers = array(new GetSetMethodNormalizer());
        $this->serializer = new Serializer($this->normalizers, $this->encoders);
    }

    public function indexAction() {
        $filename = 'lastmodification.txt';
		if (file_exists($filename)) { $lastmodification = new \DateTime(file_get_contents($filename)); }

        $addresses = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findAll();
        if (!$addresses) { throw $this->createNotFoundException('No addresses found'); }

        // http://www.testically.org/2011/08/25/using-a-unique-index-in-mongodb-with-doctrine-odm-and-symfony2/
        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->getSchemaManager()->ensureIndexes();

        return $this->render('BonnesAdressesBundle:Default:index.html.twig', array('addresses' => $addresses, 'lastmodification' => $lastmodification));
    }

    public function addressAction(Request $request) {
        $id = $request->request->get('idAddress');
        if (!$id) { throw $this->createNotFoundException('No id found'); }

        $address = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->find($id);
        if (!$address) { throw $this->createNotFoundException('No address found'); }

        return new Response($this->serializer->serialize($address, 'json'));
    }

    public function detailsAction($name) {
        $addresses = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findAll();
        if (!$addresses) { throw $this->createNotFoundException('No addresses found'); }
        $address = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findOneBySlug($name);
        if (!$address) { throw $this->createNotFoundException('No address found'); }

        return $this->render('BonnesAdressesBundle:Default:index.html.twig', array('addresses' => $addresses, 'specificAddress' => $address, 'specificAddressComplete' => $address->getAdresseComplete()));
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
