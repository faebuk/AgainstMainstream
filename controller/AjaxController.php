<?php
require_once('lib/View.php');
require_once('controller/UserController.php');
require_once('model/DBSuche.php');
require_once('model/youtube.php');
/**
 *
 * @author Fabian, Christian und Melvin
 * Hier werden die Ajax Request ausgeführt
 */
class AjaxController{
	private $bewertungsmodel = null;
	private $model = null;
	private $views = array();
	private $youtube = null;
	/**
	 * Konstruktor
	 * DB anbindungen werden erstellt.
	 */
	public function __construct(){
		$mysql = MySQL::getInstance(array('localhost', 'root', '6Vgx8qmW', 'amdb'));
		$this->bewertungsmodel = new Model($mysql, 'bewertung');
		$this->model = new Model($mysql, 'lied');
		$this->youtube = new youtube();
	}
	/**
	 * ist für die bewertung verantwortlich
	 */
	public function saverating(){
		if (isset($_SERVER['HTTP_REFERER'])){
			//falls man angemeldet ist dann Sachen von ajax-request von jquery holen
			if(!empty($_SESSION['angemeldet'])){
				//variabeln setzen von ajarax request und session
				$rating =  $_POST['score'];
				$uri = $_POST['currenturl'];
				$username = $_SESSION['username'];
				$url = explode('/', trim($uri, '/'));
				$videoid = $url[2];
			
				$this->bewertungsmodel->db->query('SELECT * FROM bewertung WHERE Benutzer_ID = (SELECT ID_Benutzer from benutzer WHERE Name = "'.$username.'") AND Lied_ID = (SELECT ID_Lied from lied WHERE Youtubelink = "'.$videoid.'")');
				$fetch = $this->bewertungsmodel->fetch();
			
				//wenn noch kein eintrag existiert dann inserten
				if (count($fetch)==0){
					$this->bewertungsmodel->db->query('INSERT INTO bewertung (Bewertung, Benutzer_ID, Lied_ID) VALUES ('.mysql_real_escape_string($rating).', (SELECT ID_Benutzer from benutzer WHERE Name = "'.$username.'"), (SELECT ID_Lied from lied WHERE Youtubelink = "'.$videoid.'"));');
					echo "Your rating counted.";
				}
				//wenn schon ein rating abgegeben wurde dann updaten
				else {
					$this->bewertungsmodel->db->query('UPDATE bewertung SET Bewertung = '.mysql_real_escape_string($rating).' WHERE Benutzer_ID = (SELECT ID_Benutzer from benutzer WHERE Name = "'.$username.'") AND Lied_ID = (SELECT ID_Lied from lied WHERE Youtubelink = "'.$videoid.'")');
					echo "Your rating got updated.";
				}
			}
			//error wenn man nicht eingeloggt
			else{
				echo "You have to be logged in to rate.";
			}
		}

	}
	/**
	 * generiert neue Videos
	 */
	public function morevideos(){
		if (isset($_SERVER['HTTP_REFERER'])){
			//Wenn das Argument lehr ist (kommt von der grund index Seite),wird das ausgeführt.
			if($_POST['argument']==""){
				//holt die nächsten Lieder aus der Datenbank
				$this->model->db->query('SELECT *, AVG( b.bewertung ) as be FROM lied AS l JOIN bewertung AS b ON b.Lied_ID = l.ID_Lied GROUP BY l.Titel order by be desc LIMIT '.($_POST['videos']).',5;');
				$fetch = $this->model->fetch();
				//hier werden die Lieder generiert
				$counter = 0;
				foreach ($fetch as $video){
					$a = $this->youtube->getParams($video);
					$a['zusatz']=$_POST['videos'];
					$view = new View('videogen', $a);
					$this->views['videogen'.$counter] = $view;
					$counter++;
				}
			}
			else{
				//holt die nächsten Lieder aus der Datenbank
				$this->model->db->query('SELECT ID_Lied, Youtubelink, Titel, Dauer FROM lied WHERE Genre_ID = (SELECT ID_Genre FROM genre WHERE Genrename = "'.$_POST['argument'].'") LIMIT '.($_POST['videos']).',5;');
				$fetch = $this->model->fetch();
				//hier werden die Lieder generiert
				$counter = 0;
				foreach ($fetch as $video){
					$a = $this->youtube->getParams($video);
					$a['zusatz']=$_POST['videos'];
					$view = new View('videogen', $a);
					$this->views['videogen'.$counter] = $view;
					$counter++;
				}
			}
			//zeigt alles aus dem views array an
			foreach ($this->views as $view){
				$view->display();
			}
		}

	}
	
	
}