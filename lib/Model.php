<?php
require_once('lib/MySQL.php');
/**
 * 
 * @author Ramon Binz
 * editiert durch Christian Ulmann
 *
 */
class Model
{
	//placeholder
    public $db = null;
    private $table = null;
    /**
     * speichert die Parameter in die oben definierten placeholder
     * @param MySQL $db
     * @param String $table
     */
    public function  __construct(MySQL $db, $table)
    {
        $this->db = $db;
		$this->table = $table;
    }
	/**
	 * führt ein Select * auf $this->table aus und gibt das SQL result zurück
	 * @return array mit objekten
	 */
    public function fetchAll()
    {
		$this->db->query('SELECT * FROM ' . $this->table);
		return $this->fetch();
    }
    /**
     * Holt die Resultate aus der DB
     * @return array mit objekten
     */
    public function fetch(){
    	$rows = array();
    	while ($row = $this->db->fetch())
    	{
    		$rows[] = $row;
    	}
    	
    	return $rows;
    }
	/**
	 * führt einen insert befehl in die $this->table aus
	 * @param array $data
	 */
    public function insert(array $data)
    {
    	if (!empty($data))
    	{
    		$data = $this->quoteStrings($data);

    		$fields = implode(',', array_keys($data));
		    $values = implode(',', array_values($data));
		    $this->db->query('INSERT INTO ' . $this->table . ' (' . $fields . ')' . ' VALUES (' . $values . ')');
    	}
    }
    /**
     * führt einen Insert befehl in der $this->table aus und schreibt die werte aus $date in den eintrag mit $id
     * @param array $data
     * @param int $id
     */
    public function update(array $data, $id){
    	if (!empty($data))
    	{
    		$data = $this->quoteStrings($data);
    		
    		$set = '';
		    foreach($data as $field => $value)
		    {
		    	$set .= $field .'=' . $value . ',';
			}
			$set = substr($set, 0, -1);
			$this->db->query('UPDATE ' . $this->table . ' SET ' . $set . ' WHERE id=' . (int)$id);	
    	}
    }
    /**
     * läscht anhand der id aus der $this->table
     * @param string $id
     */
    public function delete($id = null)
    {
        if ($id !== null)
        {
            $this->db->query('DELETE FROM ' . $this->table . ' WHERE id=' . (int)$id);
        }
    }
    /**
     * führt eine real_escape_string aus
     * @param array $data
     * @return string
     */
    private function quoteStrings(array $data){
    	foreach ($data as $field => $value)
    	{
    		$value = $this->db->link->real_escape_string($value);
    		if (!is_numeric($value))
    		{
    			$data[$field] = '\'' . $value . '\''; 
    		}
    	}
    	
    	return $data;
    }
}