<?php
/**
 * 
 * @author Ramon Binz
 * komentiert durch Christian Ulmann
 *
 */
class View
{
	//placeholder
    private $viewfile 	= null;
    private $properties = array();
	/**
	 * Konstruktor
	 * hier wird das $viewfile und das $properties array zwischengespeichert
	 * @param string $viewfile
	 * @param unknown $properties
	 */
    public function __construct($viewfile = '', $properties = array())
    {
    	$this->properties = $properties;
    	
    	$viewfile = './view/' . $viewfile . '.php';
    	if (file_exists($viewfile))
	    {
	       $this->viewfile = $viewfile;
	    }
    }
    /**
     * hiermit kann man im $this->propertiers verschiedene Wert anpassen
     * @param unknown $property
     * @param unknown $value
     */
    public function __set($property, $value)
    {
        if (!isset($this->$property))
        {
            $this->properties[$property] = $value;
        }
    }
	/**
	 * hiermit kann man aus dem $this->propertiers verschiedene Wert holen
	 * @param unknown $property
	 * @return multitype:
	 */
    public function __get($property)
    {
        if (isset($this->properties[$property]))
        {
            return $this->properties[$property];
        }
    }
	/**
	 * übergibt dem viewfile die Properties und das viewfile wird geladen.
	 */
    public function display()
    {
        extract($this->properties);
        require($this->viewfile);
    }
}