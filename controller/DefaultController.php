<?php
require_once('lib/View.php');
require_once('controller/UserController.php');
require_once('model/DBSuche.php');
require_once('model/youtube.php');
/**
 *
 * @author Fabian, Christian und Melvin
 * Verwaltet die ganzen /Default/* Seiten
 */
class DefaultController{
	//Placeholders
	private $songmodel = null;
	private $youtube = null;
	private $kommentarmodel = null;
	private $out = null;
	private $bewertungsmodel = null;
	private $views = array();	
	/**
	 * Konstruktor, wird beim erstellen einer Instanz ausgeführt
	 * Erstellt header, nav und obernav. zudem werden Datenbankanbindungen erstellt
	 */
	public function __construct(){
		$out = new View('header', array('title' => 'againstMainstream', 'currentcss' => '', 'logo' => 'logo'));
		$this->views['header'] = $out;
		$view = new View('nav', array('momentan' => 'edit'));
		$this->views['nav'] = $view;
		$view = new View('oberenav');
		$this->views['oberenav'] = $view;
		
		//MySQL singelton Instanz sowie Models dafür.
		$mysql = MySQL::getInstance(array('localhost', 'root', '6Vgx8qmW', 'amdb'));
		$this->songmodel = new Model($mysql, 'benutzer');
		$this->kommentarmodel = new Model($mysql, 'kommentar');
		$this->youtube = new youtube();
		$this->bewertungsmodel = new Model($mysql, 'bewertung');
	}
	
	/**
	 * 
	 * @param String $argument
	 * erstellt die Index Seite
	 * wird standartmässig aufgeruft
	 */
	public function index($argument){	
		//Wenn etwas im argument (vom Dispatcher übergeben) drin ist diese Anzeigen, man ist in einem Navpunkt
		if (!empty($argument)){
			//Argument das übergeben wird ist immer die Genre bsp. 			
			$genre = $argument;
			$this->views['header']->title = $genre.' - againstMainstream';
			$this->views['header']->currentcss = strtolower($genre);
			
			$view = new View('suche');
			$this->views['suche'] = $view;
			$view = new View('videosubmit');
			$this->views['videosubmit'] = $view;
			//Datenbank abfrage für die ersten 5 Elemente mit der Genre die übergeben wird
			$this->songmodel->db->query('SELECT ID_Lied, Youtubelink, Titel, Dauer FROM lied WHERE Genre_ID = (SELECT ID_Genre FROM genre WHERE Genrename = "'.$genre.'") limit 5;');
			//Ergebnis fetchen, ist nun sogesagt als Array verfügbar
			$fetch = $this->songmodel->fetch();
			
			//Wenn mehr als 1 Zeile aus der Datenbank zurück kommt diese und die anderen auflisten
			if (count($fetch) > 0){
				$view = new View('startallvideos',array('argument' => $argument));
				$this->views['startallvideos'] = $view;
				$counter = 0;
				foreach ($fetch as $video){
					$a = $this->youtube->getParams($video);
					$view = new View('videogen', $a);
					$this->views['videosubmit'.$counter] = $view;
					$counter++;
				}
				$view = new View('stopallvideos');
				$this->views['stopallvideos'] = $view;
				$view = new View('more');
				$this->views['more'] = $view;
			}
			
			//Sonst ist die Seite leer
			else {
				if (!empty($_SERVER['HTTP_REFERER'])){
					$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
				}
				else {
					$url = '/Default/index';
				}
				$view = new View('failed', array('message' => "The list seems to be empty.", 'back' => $url));
				$this->views['failed'] = $view;
			}
		}
		
		//Wenn $argument (wird vom Dispatcher übergeben) leer ist dann die ganz normale Index laden.
		else {
			$this->views['header']->title = 'againstMainstream';
			$view = new View('suche');
			$this->views['suche'] = $view;
			$view = new View('videosubmit');
			$this->views['videosubmit'] = $view;

			//Die Top 5 Bewertungen aus der Datenbank auslesen
			$this->songmodel->db->query('SELECT *, AVG( b.bewertung ) as be FROM lied AS l JOIN bewertung AS b ON b.Lied_ID = l.ID_Lied GROUP BY l.Titel order by be desc LIMIT 5;');

			$fetch = $this->songmodel->fetch();
			$view = new View('startallvideos',array('argument' => $argument));
			$this->views['startallvideos'] = $view;
			$counter = 0;
			foreach ($fetch as $video){
				$a = $this->youtube->getParams($video);
				$view = new View('videogen', $a);
				$this->views['videogen'.$counter] = $view;
				$counter++;
			}
			$view = new View('stopallvideos');
			$this->views['stopallvideos'] = $view;
			$view = new View('more');
			$this->views['more'] = $view;
		}
	}
	/**
	 * 
	 * @param String $a (youtube link aus DB)
	 * Seite für die Details der Videos (wenn man aufs Bild klickt)
	 */
	public function details($a){
		//$a ist Argument vom Dispatcher (videoid)
		$view = new View('suche');
		$this->views['suche'] = $view;
		$view = new View('videosubmit');
		$this->views['videosubmit'] = $view;
		$videoid = $a;
	
		$this->songmodel->db->query('SELECT Titel, Dauer FROM lied WHERE Youtubelink = "'.$videoid.'";');
		$fetch = $this->songmodel->fetch();
		//Wenn Video existiert dass dies hier alles machen
		if (count($fetch) > 0){
			
			//Sachen aus Datenbank auslesen
			$duration = gmdate("H:i:s", $fetch[0]->Dauer);
			$title = $fetch[0]->Titel;
			
			//Genre vom Lied aus der Datenbank holen
			$this->songmodel->db->query('SELECT Genrename FROM lied JOIN genre ON Genre_ID = ID_Genre WHERE Youtubelink = "'.$videoid.'"');
			$fetchgenre = $this->songmodel->fetch();
			$genre = $fetchgenre[0]->Genrename;
			$genre = strtolower($genre);
			//Bei header.php das Current css zur Genrename + .css ändern (für die verschiedenen Colors)
			$this->views['header']->currentcss = $genre;
			
			//Daten die man auf der Website braucht von Youtube holen
			$a = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$videoid.'&key=AIzaSyCHwZBtG3Y89NQffIXKCdlRnduFVgWR4JI&part=snippet,contentDetails,statistics,status');
			$data = json_decode($a);
			$views = $data->{'items'}[0]->{'statistics'}->{'viewCount'};
			$viewsformat = number_format($views, 0, '', "'");
		
			//Alle Kommentare + Benutzer aus Datenbank auslesen
			$this->kommentarmodel->db->query('SELECT Name, Kommentar FROM kommentar JOIN lied ON ID_Lied = Lied_ID JOIN benutzer on ID_Benutzer = Benutzer_ID WHERE Youtubelink = "'.$videoid.'";');
			$allcomments = $this->kommentarmodel->fetch();
		
			//Wenn man eingeloggt ist die Bewertung so einstellen wie der Benutzer gerated hat.
			if (isset($_SESSION['username'])){
				$this->bewertungsmodel->db->query('SELECT Bewertung FROM bewertung WHERE Benutzer_ID = (SELECT ID_Benutzer FROM benutzer WHERE Name = "'.$_SESSION['username'].'") AND Lied_ID = (SELECT ID_Lied from lied WHERE Youtubelink = "'.$videoid.'")');
				$fetch = $this->bewertungsmodel->fetch();
				if (count($fetch)>0){
					$userrating = $fetch[0]->Bewertung;
				}
				else {
					$userrating = 0;
				}
			}
			else {
				$userrating = 0;
			}
		
			//Restlichen Schrott einfügen
			$view = new View('details', array('title' => $title, 'videoid' => $videoid, 'views' => $viewsformat, 'duration' => $duration, 'userrating' => $userrating));
			$this->views['details'] = $view;
		
			$view = new View('detailscomment', array('videoid' => $videoid));
			$this->views['detailscomment'] = $view;
		
			//Wenn kein Kommentar eingetragen ist displayen dass kein Kommentar vorhanden ist
			if (count($allcomments) == 0){
				$view = new View('commentsstart', array('title' => 'There are currently no comments.'));
				$this->views['commentsstart'] = $view;
			}
			//Sonst mit den Comments 'starten'
			else {
				$view = new View('commentsstart', array('title' => 'Comments: '));
				$this->views['commentsstart'] = $view;
			}
			
			$counter = 0;
			foreach ($allcomments as $comment){
				$view = new View('commentsgen', array('username' => $comment->Name, 'comment' => $comment->Kommentar));
				$this->views['commentsgen'.$counter] = $view;
				$counter++;
			}
			
			$view = new View('commentsstop');
			$this->views['commentsstop'] = $view;
			
			//Titel im header.php ändern
			$this->views['header']->title = $title;
		}
		//Sonst Error geben dass das Video nicht existiert
		else {
			if (!empty($_SERVER['HTTP_REFERER'])){
				$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
			}
			else {
				$url = '/Default/index';
			}
			$view = new View('failed', array('message' => "This video doesn't exist", 'back' => $url));
			$this->views['failed'] = $view;
		}

	}
	/**
	 * Funktion für das Registrierungsformular
	 */
	public function registrierung(){
		$this->views['header']->title = 'Sign up - againstMainstream';
		$view = new View('registrierung');
		$this->views['registrierung'] = $view;
	}
	/**
	 * Formular für erweiterte Suche
	 */
	public function erweitert(){
		$this->views['header']->title = 'Advanced search - againstMainstream';
		$view = new View('erweitertesuche');
		$this->views['erweitertesuche'] = $view;
	}
	/**
	 * Resultate für die erweiterte Suche
	 */
	public function erweitertAnzeigen(){
		if (isset($_SERVER['HTTP_REFERER'])){
			$this->views['header']->title = 'Advanced search - againstMainstream';
			$view = new View('erweitertesuche', array('post' => $_POST));
			$this->views['erweitertesuche'] = $view;
			$test = new DBSuche();
			//nimmt alle werte aus der $_Post variable
			$titel = $_POST['titel']!= 'Title' ? $_POST['titel'] : null;
			$bewertung = $_POST['bewertung']!= 'All' ? $_POST['bewertung'] : null;
			$genre = $_POST['genre']!= 'All' ? $_POST['genre'] : null;
			//erstellt das views array
			if ($_POST['view1'] != 0 || $_POST['view2'] !=1000000) {
				$views = array($_POST['view1'],$_POST['view2']);
			} else {
				$views = null;
			}
			//Errechnet die Dauer in Sekunden 
			if ($_POST['dauer1']!= ""||$_POST['dauer2']!== ""||$_POST['dauer3']!=""||$_POST['dauer4']!=""||$_POST['dauer5']!=""||$_POST['dauer6']!="") {
				$d1 = $_POST['dauer3']+$_POST['dauer2']*60+$_POST['dauer1']*3600;
				$d2 = $_POST['dauer6']+$_POST['dauer5']*60+$_POST['dauer4']*3600;
				$dauer = array($d1,$d2);
			} else{
				$dauer = null;
			}
			//Sucht via DBSuche.php in der Datenbank nach ergebnissen und übergibt die erstellungs Parameter
			$load = $test->suchen(array(
								'titel' => $titel,
								'bewertung' => $bewertung,
								'views' => $views,
								'genre' => $genre,
								'dauer' => $dauer,
							));
			$counter = 0;
			//erstellt die resultate
			foreach ($load as $id){
				$this->songmodel->db->query('SELECT * FROM lied where ID_Lied = "'.$id.'";');
				$fetch = $this->songmodel->fetch();
				$a = $this->youtube->getParams($fetch[0]);
				$view = new View('videogen', $a);
				$this->views['erweitertevideos'.$counter] = $view;
				$counter++;
			}
		}
		else {
			$this->views['header']->logo = 'logo';
			$view = new View('403');
			$this->views['403'] = $view;
			unset($this->views['nav']);
			unset($this->views['oberenav']);
			$view = new View('divopen');
			$this->views['divopen'] = $view;
		}
	}
	/**
	 * Funktion für Ergebnsise der leichten Suche
	 */
	public function suche(){
		if (isset($_SERVER['HTTP_REFERER'])){
			$suche = $_POST['suche'];
			
			$view = new View('suche', array('suche'=>$suche));
			$this->views['suche'] = $view;
			$view = new View('videosubmit');
			$this->views['videosubmit'] = $view;
			$test = new DBSuche();
			//Sucht in der DB nach den suchergebnissen und giebt ihre Parameter zurück
			$load = $test->suchen(array(
					'titel' => $suche,
					'bewertung' => null,
					'views' => null,
					'genre' => null,
					'dauer' => null,
			));
			$counter = 0;
			//erstellt die resultate
			foreach ($load as $id){
				$this->songmodel->db->query('SELECT * FROM lied where ID_Lied = "'.$id.'";');
				$fetch = $this->songmodel->fetch();
				$a = $this->youtube->getParams($fetch[0]);
				$view = new View('videogen', $a);
				$this->views['erweitertevideo'.$counter] = $view;
				$counter++;
			}
		}
		else {
			$this->views['header']->logo = 'logo';
			$view = new View('403');
			$this->views['403'] = $view;
			unset($this->views['nav']);
			unset($this->views['oberenav']);
			$view = new View('divopen');
			$this->views['divopen'] = $view;
		}		
	}
	/**
	 * Funktion zum überprüfen des Logins
	 */
	public function login(){
		$mysql = MySQL::getInstance(array('localhost', 'root', '', 'amdb'));
		$this->model = new Model($mysql, 'benutzer');
		
		//Wenn Benutzername und Password eingegeben wurden dann weiter fahren
		if(isset($_POST['email']) && $_POST['password']){
			$this->model->db->query('Select * from benutzer where Email = "'.$_POST['email'].'" and Password ="'.md5($_POST['password']).'";');
			//Schauen ob ein user in der Datenbank mit diesem Email und dem Password in der Datenbank gibt
			//Wenn ja gibt es 1 Zeile aus, also wenn es eine Zeile ausgibt dann hat man sich richtig eingeloggt
			if ($this->model->db->countRows()!=0){
				if (!isset($_SESSION["count"]))
				{
					$_SESSION['count'] = 0;
					$_SESSION['start'] = time(  );
				}
				else
				{
					$_SESSION['count'] = $_SESSION['count'] + 1;
				}
				$sessionId = session_id();
				
				//Verschiedene Sachen aus Datenbank in die Session Variabeln speichern 			
				$fetch = $this->model->fetch();
				$_SESSION['id'] = $fetch[0]->ID_Benutzer;
				$this->model->db->query('Select Rolle_ID from benutzer where ID_Benutzer = ' . $fetch[0]->ID_Benutzer);
				$fetch = $this->model->fetch();
				$_SESSION['angemeldet'] =$fetch[0]->Rolle_ID;
				$this->model->db->query('Select * from benutzer where Email = "'.$_POST['email'].'" and Password ="'.md5($_POST['password']).'";');
				$fetch = $this->model->fetch();
				$this->model->db->query('Select Name from benutzer where ID_Benutzer = ' . $fetch[0]->ID_Benutzer);
				$fetch = $this->model->fetch();
				$_SESSION['username'] = $fetch[0]->Name;
				$_SESSION['email'] = $_POST['email'];

				$url = '/Default/index';
				$view = new View('success', array('message' => 'You have been logged in.', 'back' => $url));
				$this->views['successlogin'] = $view;
				
			}else{
				$this->views['oberenav']->error = true;
			}
		}
		//Sonst Meldung geben dass etwas von beidem nicht eingegeben wurde
		else {
			if (!empty($_SERVER['HTTP_REFERER'])){
				$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
			}
			else {
				$url = '/Default/index';
			}
			$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
			$view = new View('failed', array('message' => "Please enter your username and password.", 'back' => $url));
			$this->views['failed'] = $view;
		}
	}
	/**
	 * zeigt den 404 Error
	 */
	public function show404(){
		$this->views['header']->logo = '404logo';
		$view = new View('404');
		$this->views['404'] = $view;
		unset($this->views['nav']);
		unset($this->views['oberenav']);
		$view = new View('divopen');
		$this->views['divopen'] = $view;
	}
	/**
	 * zeigt den 403 Error
	 */
	public function show403(){
		$this->views['header']->logo = 'logo';
		$view = new View('403');
		$this->views['403'] = $view;
		unset($this->views['nav']);
		unset($this->views['oberenav']);
		$view = new View('divopen');
		$this->views['divopen'] = $view;
	}
	/**
	 * Hier werden alle Objekte im Array $views displayen
	 */
	public function __destruct(){
		$out = new View('footer');
		$this->views['footer'] = $out;
		foreach ($this->views as $view){
			$view->display();
		}
	}
}