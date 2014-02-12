<?php
/**
 * 
 * @author Christian Ulmann
 * Diese Klasse dient dazu verschiedenen Sachen aus der Datenbank zu suchen und id's zurückzugeben.
 *
 */
class DBSuche{
	//placeholder
	private $ergebnisse = array();
	private $model = null;
	private $titel = null;
	private $bewertung = null;
	private $views = null;
	private $genre = null;
	private $dauer = null;
	/**
	 * Such anhanden der Parameter, welche im array $a sind, die lieder die den kriterien zutreffen
	 * @param string $a
	 * @return array
	 */
	public function suchen($a = null){
		//Erstellt model mit anbindung zur Tabelle lied
		$mysql = MySQL::getInstance(array('localhost', 'root', '', 'amdb'));
		$this->model = new Model($mysql, 'lied');
		if($a!=null){
		$this->titel = $a['titel'];
		$this->bewertung = $a['bewertung'];
		$this->views = $a['views'];
		$this->genre = $a['genre'];
		$this->dauer = $a['dauer'];
		if($this->views[0]!="" and $this->views[1]==""){
			$this->views[1] = "1000000";
		}
		if($this->views[0]=="" and $this->views[1]==""){
			$this->views = null;
		}
		}
		//schreibt alle ids in ein das array ergebnisse
		$this->ergebnisse = $this->getID_Lied($this->model->fetchAll());
		//führt die Titelsuche durch
		if($this->titel!=null){
			$tmp = array();
			foreach ($this->ergebnisse as $e){
				$this->model->db->query('Select * from lied where Titel like "%'.$this->titel.'%" and ID_Lied = '.$e.';');
				if ($this->model->db->countRows()!=0){
					array_push($tmp, $e);
				}
			}
			//schreibt die ergebnisse in das $this->ergebnisse array
			$this->ergebnisse = $tmp;
		}
		//führt die Bewertungssuche durch
		if ($this->bewertung !=null) {
			
			$tmp = array(); 
			foreach ($this->ergebnisse as $e){
				//es werden nur die ids geprüft, welche noch im $this->ergebnisse sind
				$this->model->db->query('Select AVG(Bewertung) as be from bewertung where Lied_ID = '.$e.';');
				$test = $this->model->fetch()->be;
				if($test != null){
					$this->model->db->query('Select * from lied where '.round($test).'='.$this->bewertung.' and ID_Lied = '.$e.';');
					if ($this->model->db->countRows()!=0){
						array_push($tmp, $e);
					}
				}
			}
			//schreibt die ergebnisse in das $this->ergebnisse array
			$this->ergebnisse = $tmp;
		}
		//führt die genresuche durch
		if($this->genre != null){
			$tmp = array();
			foreach ($this->ergebnisse as $e){
				//es werden nur die ids geprüft, welche noch im $this->ergebnisse sind
				$this->model->db->query('Select * from lied where Genre_ID = (Select ID_Genre from genre where Genrename = "'.$this->genre.'") and ID_Lied = '.$e.';');
				if ($this->model->db->countRows()!=0){
					array_push($tmp, $e);
				}
			}
			//schreibt die ergebnisse in das $this->ergebnisse array
			$this->ergebnisse = $tmp;
		}
		//führt die dauersuche durch
		if($this->dauer != null){
			$tmp = array();
			foreach ($this->ergebnisse as $e){
				//es werden nur die ids geprüft, welche noch im $this->ergebnisse sind
				$this->model->db->query('Select * from lied where Dauer between '.$this->dauer[0].' and '.$this->dauer[1].' and ID_Lied = '.$e.';');
				if ($this->model->db->countRows()!=0){
					array_push($tmp, $e);
				}
			}
			//schreibt die ergebnisse in das $this->ergebnisse array
			$this->ergebnisse = $tmp;
		}
		//führt die viewssuche durch
		if ($this->views != null) {
			$tmp = array();
			$youtube = new youtube();
			foreach ($this->ergebnisse as $e){
				//es werden nur die ids geprüft, welche noch im $this->ergebnisse sind
				//dabei muss es aber die youtube api verwenden
				$this->model->db->query('Select * from lied where ID_Lied = '.$e.';');
				$video = $this->model->fetch();
				$params = $youtube->getParams($video);
				$params['views'] = str_replace("'","", $params['views']);
				if (($params['views'] > $this->views[0]) && ($params['views'] < $this->views[1])) {
					array_push($tmp, $e);
				}
			}
			//schreibt die ergebnisse in das $this->ergebnisse array
			$this->ergebnisse = $tmp;
		}
		return $this->ergebnisse;
	}
	/**
	 * gibt alle IDs aus der DB zurück
	 * @param unknown $tmp
	 * @return array
	 */
	public function getID_Lied($tmp){
		//schreibt alle ID_lied in das array ergebnisse
		$ergebnisse = array();
		foreach ($tmp as $row){
			array_push($ergebnisse,$row->ID_Lied);
		}
		return $ergebnisse;
	}
}