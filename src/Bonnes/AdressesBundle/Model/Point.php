<?php

namespace Bonnes\AdressesBundle\Model;

class Point
{
	protected $type;
	protected $properties;
	protected $geometry;

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return Properties
	 */
	public function getProperties()
	{
		return $this->properties;
	}

	/**
	 * @param Properties $properties
	 */
	public function setProperties(Properties $properties)
	{
		$this->properties = $properties;
	}

	/**
	 * @return Geometry
	 */
	public function getGeometry()
	{
		return $this->geometry;
	}

	/**
	 * @param Geometry $geometry
	 */
	public function setGeometry(Geometry $geometry)
	{
		$this->geometry = $geometry;
	}
}
