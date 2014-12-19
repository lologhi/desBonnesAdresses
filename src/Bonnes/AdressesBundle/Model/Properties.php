<?php

namespace Bonnes\AdressesBundle\Model;


use JMS\Serializer\Annotation\SerializedName;

class Properties
{
    private $name;
    /**
     * @SerializedName("marker-symbol")
     */
    private $markerSymbol;
    /**
     * @SerializedName("marker-size")
     */
    private $markerSize;
    /**
     * @SerializedName("marker-color")
     */
    private $markerColor;
    private $slug;
    private $adresse;
    private $codePostal;
    private $ville;
    private $url;
    private $origine;
    private $prix;
    private $description;
    private $telephone;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getMarkerSymbol()
    {
        return $this->markerSymbol;
    }

    /**
     * @param mixed $markerSymbol
     */
    public function setMarkerSymbol($markerSymbol)
    {
        $this->markerSymbol = $markerSymbol;
    }

    /**
     * @return mixed
     */
    public function getMarkerSize()
    {
        return $this->markerSize;
    }

    /**
     * @param mixed $markerSize
     */
    public function setMarkerSize($markerSize)
    {
        $this->markerSize = $markerSize;
    }

    /**
     * @return mixed
     */
    public function getMarkerColor()
    {
        return $this->markerColor;
    }

    /**
     * @param mixed $markerColor
     */
    public function setMarkerColor($markerColor)
    {
        $this->markerColor = $markerColor;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * @param mixed $codePostal
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param mixed $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getOrigine()
    {
        return $this->origine;
    }

    /**
     * @param mixed $origine
     */
    public function setOrigine($origine)
    {
        $this->origine = $origine;
    }

    /**
     * @return mixed
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param mixed $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }
}
