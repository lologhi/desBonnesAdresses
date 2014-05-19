<?php

namespace Bonnes\AdressesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdresseTypeController extends Controller {

	public function filterAction() {
		$addresseTypes = $this->get('doctrine_mongodb')->getRepository('BonnesAdressesBundle:AdresseType')->findAll();

		return $this->render('BonnesAdressesBundle:AdresseType:filter.html.twig', array('addresseTypes' => $addresseTypes));
	}

}
