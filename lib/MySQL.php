<?php
/**
 * 
 * @author Ramon Binz
 * komentiert von Christian Ulmann
 * MySQL ist singleton
 */
class MySQL
{
	//placeholder
	private $result = null;
	public $link = null;
	private static $instance = null;
	/**
	 * instanziert sich selber wenn das noch nicht surchgeführt wurde und 
	 * übergiebt sich selber als instanz (singleton)
	 * @param array $config
	 * @return MySQL
	 */
	public static function getInstance(array $config = array())
	{
		if (self::$instance === null)
		{
			self::$instance = new self($config);
		}
		return self::$instance;
	}
	/**
	 * konstruktor, baut eine DB anbindung anhanden des $config arrays
	 * @param array $config
	 * @throws Exception
	 */
	public function __construct(array $config = array())
	{
		list($host, $user, $password, $database) = $config;
		if ((!$this->link = mysqli_connect($host, $user, $password, $database)))
		{
			throw new Exception('Verbindungsfehler: ' . mysqli_connect_error());
		}
	}
	/**
	 * führt einen Query aus und speichert das resultat in $this->result
	 * @param unknown $query
	 * @throws Exception
	 */
    public function query($query)
    {
        if (is_string($query) and !empty($query))
        {
            if ((!$this->result = mysqli_query($this->link, $query)))
            {	
                throw new Exception('Queryfehler: ' . $query . ' Fehlermeldung : ' . mysqli_error($this->link));
            }
        }
    }
    /**
     * gibt die letzte zeile vom resultat als objekt zurück
     * @return boolean|object
     */
	public function fetch()
	{
		if ((!$row = mysqli_fetch_object($this->result)))
		{
			mysqli_free_result($this->result);
            return false;
        }
        return $row;
	}
    /**
     * zählt die anzahl der zeilen im resultat
     * @return number
     */    
     public function countRows()
    { 
        if ($this->result != NULL)
        {
           return mysqli_num_rows($this->result); 
        }
        return 0;
    }
    /**
     * schliest die DB verbindung
     */
	function __destruct()
	{
		is_resource($this->link) and mysqli_close($this->link);
	}
}