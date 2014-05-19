<?php

namespace Bonnes\AdressesBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Gedmo\Mapping\Annotation as Gedmo;

/**
* @MongoDB\Document(collection="AdresseType")
*/
class AdresseType {

	public function __toString() {
		return $this->name;
	}

	/**
	* @MongoDB\Id
	*/
	protected $id;

	/**
	* @MongoDB\String
	*/
	protected $name;

	/**
	* Get id
	*
	* @return id $id
	*/
	public function getId()
	{
		return $this->id;
	}

	/**
	* Set name
	*
	* @param string $name
	* @return self
	*/
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	* Get name
	*
	* @return string $name
	*/
	public function getName()
	{
		return $this->name;
	}

}
