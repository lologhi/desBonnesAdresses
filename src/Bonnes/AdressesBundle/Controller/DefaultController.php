<?php

namespace Bonnes\AdressesBundle\Controller;

use Bonnes\AdressesBundle\Model\Geometry;
use Bonnes\AdressesBundle\Model\Point;
use Bonnes\AdressesBundle\Model\Properties;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Gedmo\Sluggable\Util as Sluggable;

class DefaultController extends Controller {

	/**
	 * @Template
	 */
    public function indexAction($slug = null) {
        $filename = 'lastmodification.txt';
		if (file_exists($filename)) { $lastmodification = new \DateTime(file_get_contents($filename)); }

        $addresses = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findAll();
        if (!$addresses) { throw $this->createNotFoundException('No addresses found'); }
		
		if ($slug) {
	        $address = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->findOneBySlug($slug);
	        if (!$address) { throw $this->createNotFoundException('No address found'); }
		} else {
			$address = '';
		}

        // http://www.testically.org/2011/08/25/using-a-unique-index-in-mongodb-with-doctrine-odm-and-symfony2/
        //$dm = $this->get('doctrine_mongodb')->getManager();
        //$dm->getSchemaManager()->ensureIndexes();

        return array('points' => $this->get('geojsonmaker')->fromDoctrine($addresses), 'specificAddress' => $address, 'lastmodification' => $lastmodification);
    }

	/**
	 * @Template
	 */
    public function filterAction() {
        $types = $this->get('doctrine_mongodb')->getManager()
            ->createQueryBuilder('BonnesAdressesBundle:Adresse')
            ->distinct('marker')
            ->getQuery()
            ->execute();

        return array('types' => $types);
    }

    public function addressAction(Request $request) {
        $id = $request->request->get('idAddress');
        if (!$id) { throw $this->createNotFoundException('No id found'); }

        $address = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:Adresse')->find($id);
        if (!$address) { throw $this->createNotFoundException('No address found'); }

        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array(new JsonEncoder()));

        return new Response($serializer->serialize($address, 'json'));
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
