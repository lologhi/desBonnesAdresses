<?php

namespace Bonnes\AdressesBundle\Services;



use Bonnes\AdressesBundle\Model\Geometry;
use Bonnes\AdressesBundle\Model\Point;
use Bonnes\AdressesBundle\Model\Properties;
use JMS\Serializer\Serializer;

class GeojsonMaker
{
    private $jmsSerializer;

    public function __construct(Serializer $jmsSerializer)
    {
        $this->jmsSerializer = $jmsSerializer;
    }

    public function fromDoctrine($addresses)
    {
        $points = array();
        foreach ($addresses as $address) {
            $properties = new Properties();
            $properties->setName($address->getName());
            $properties->setSlug($address->getSlug());
            $properties->setMarkerSymbol($address->getMarker());
            $properties->setMarkerSize('small');
            $properties->setAdresse($address->getAdresseComplete());
            $properties->setUrl($address->getUrl());
            $properties->setOrigine($address->getOrigine());
            $properties->setPrix($address->getPrix());
            $properties->setDescription($address->getDescription());
            $properties->setTelephone($address->getTelephone());

            $geometry = new Geometry();
            $geometry->setType('Point');
            $geometry->setCoordinates(array($address->getLongitude(), $address->getLatitude()));

            $point = new Point();
            $point->setType('Feature');
            $point->setGeometry($geometry);
            $point->setProperties($properties);
            $points[] = $point;
        }

        return $this->jmsSerializer->serialize($points, 'json');
    }
}
