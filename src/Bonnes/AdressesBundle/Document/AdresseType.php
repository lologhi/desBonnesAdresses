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
	* @MongoDB\String
	*/
	protected $description;

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

	/**
	* Set description
	*
	* @param string $description
	* @return self
	*/
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	/**
	* Get description
	*
	* @return string $description
	*/
	public function getDescription()
	{
		return $this->description;
	}

}
