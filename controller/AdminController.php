<?php
require_once('lib/View.php');
require_once('lib/Model.php');
/**
 * 
 * @author Fabian, Christian und Melvin
 * Verwaltet die ganzen /Admin/* Seiten 
 */
class AdminController{
	//placeholder
	private $usermodel;
	private $views = array();
	/**
	 * Konstruktor
	 * erstellt header und die Datenbankanbindung
	 */
	public function __construct(){
		$out = new View('header', array('title' => 'Against Mainstream'));
		$this->views['header'] = $out;
		
		$mysql = MySQL::getInstance(array('localhost', 'root', '6Vgx8qmW', 'amdb'));
		$this->usermodel = new Model($mysql, 'benutzer');
	}
	/**
	 * generiert das ganze Adminceter, welches sich im index befindet
	 */
	public function index(){
		if (isset($_SESSION['angemeldet'])){
			//überprüft ab man Administrator ist
			if ($_SESSION['angemeldet'] == 1){
				//überprüft ob die Request vom Admincenter selbst kommt (da dort  $_Post übergeben wird)
				if($_POST){
					//wenn die user berarbeitet wurden wird das folgende ausgeführt
					if($_POST['type']=="user"){
						$tmp = array();
						$this->usermodel->db->query("Select Max(ID_Benutzer) as ID from benutzer;");
						$fetch = $this->usermodel->fetch()[0]->ID;
						//löscht oder beasrbeitet anhanden der $_Post Daten die benutzer tabelle aus der DB
						for($i=1;$i!=$fetch+1;$i++){
							if(isset($_POST[$i])){
								if ($_POST[$i]=='delete') {
									$this->usermodel->db->query('delete from benutzer where id_benutzer = "'.$i.'";');
								}
								else{
									$this->usermodel->db->query('update benutzer set Name="'.$_POST['username'.$i].'",Email="'.$_POST['email'.$i].'", Rolle_ID=(Select ID_Rolle from rolle where Rollenname="'.$_POST['group'.$i].'") where ID_Benutzer = "'.$i.'";');
								}
							}
						}		
					}
					//Wenn die video tabelle bearbeitet wurde, wird das folgande ausgeführt
					elseif($_POST['type']=="video"){
						$tmp = array();
						$this->usermodel->db->query("Select Max(ID_Lied) as ID from lied;");
						$fetch = $this->usermodel->fetch()[0]->ID;
						//löscht oder beasrbeitet anhanden der $_Post Daten die lied tabelle aus der DB
						for($i=1;$i!=$fetch+1;$i++){
							if(isset($_POST[$i])){
								if ($_POST[$i]=='delete') {
									$this->usermodel->db->query('delete from lied where id_lied = "'.$i.'";');
								}
								else{
									$this->usermodel->db->query('update lied set Titel="'.$_POST['titel'.$i].'", Genre_ID=(Select ID_Genre from Genre where Genrename="'.$_POST['genre'.$i].'") where ID_Lied = "'.$i.'";');
								}
							}
						}
					}
				}
				//header und userstart werden geladen (das formular wird gestartet)
				$this->views['header']->title = 'Admincenter - againstMainstream';
				$view = new View('userstart');
				$this->views['userstart'] = $view;
				//holt alle Benutzer aus der datenbank
				$this->usermodel->db->query('SELECT ID_Benutzer, Name, Email , Rollenname FROM benutzer join rolle on Rolle_ID = ID_Rolle');
				$users = $this->usermodel->fetch();
				
				
				$counter = 0;
				//Erstellt die benutzer Tabelle anhanden der Daten aus den $users array
				foreach ($users as $user){
					
					$id = $user->ID_Benutzer;
					$username = $user->Name;
					$email = $user->Email;
					$group = $user->Rollenname;
					//Erstellt jedesmal ein neues usergen
					$view = new View('usergen', array('id' => $id, 'username' => $username, 'email' => $email, 'group' => $group));
					$this->views['usergen'.$counter] = $view;
					
					$counter++;
				}
				//Das userstop wird geladen (das User-formular wird beendet)
				$view = new View('userstop');
				$this->views['userstop'] = $view;
				//Das adminvideostart wird geladen (das Video-formular wird gestartet)
				$view = new View('adminvideostart');
				$this->views['adminvideostart'] = $view;
				//Holt alle videos aus der DB
				$this->usermodel->db->query('SELECT ID_Lied, Youtubelink, Genrename, Titel, Dauer FROM lied join genre on Genre_ID = ID_Genre;');
				$videos = $this->usermodel->fetch();
				
				//Erstellt die Video Tabelle anhanden der Daten aus den $videos array
				$counter = 0;
				foreach ($videos as $video){
					$id = $video->ID_Lied;
					$videoid = $video->Youtubelink;
					$duration = $video->Dauer;
					$title = $video->Titel;
					$genre = $video->Genrename;
					//holt die Views von youtube
					$a = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$videoid.'&key=AIzaSyCHwZBtG3Y89NQffIXKCdlRnduFVgWR4JI&part=snippet,contentDetails,statistics,status');
					$data = json_decode($a);
					$thumbnail = $data->{'items'}[0]->{'snippet'}->{'thumbnails'}->{'default'}->{'url'};
					$views = $data->{'items'}[0]->{'statistics'}->{'viewCount'};
					$viewsformat = number_format($views, 0, '', "'");
					//holt die AnzahlBewertung aus der DB
					$this->usermodel->db->query('SELECT COUNT(Bewertung) as AnzahlBewertungen FROM bewertung WHERE Lied_ID = '.$id);
					$bewertung = $this->usermodel->fetch();
					$bewertung = $bewertung[0]->AnzahlBewertungen;
					//holt die AnzahlKommentare aus der DB
					$this->usermodel->db->query('SELECT COUNT(ID_Kommentar) as AnzahlKommentare FROM kommentar WHERE Lied_ID = '.$id);
					$kommentar = $this->usermodel->fetch();
					$kommentar = $kommentar[0]->AnzahlKommentare;
					
					//erstellt ein adminvideogen und übergibt die oben geholten Parameter
					$view = new View('adminvideogen', array('id' => $id, 'videoid' =>$videoid, 'duration'=>$duration, 'title'=>$title, 'genre'=>$genre, 'views' => $viewsformat, 'bewertung'=>$bewertung, 'kommentare'=>$kommentar));
					$this->views['adminvideogen'.$counter] = $view;
					
					$counter++;
				}
				//ein Adminviedeostop wird geladen (Video-Formular wird beendet)
				$view = new View('adminvideostop');
				$this->views['adminvideostop'] = $view;
			}
		} else{
			//wenn der Benutzer nicht berechtig ist, wird ein 403 Error ausgegeben
			$view = new View('403');
			$this->views['403'] = $view;
			unset($this->views['nav']);
			unset($this->views['oberenav']);
			$view = new View('divopen');
			$this->views['divopen'] = $view;
		}
	}
	/**
	 * alle view aus dem array views werden angezeigt
	 */
	public function __destruct(){
		$out = new View('footer');
		$this->views['footer'] = $out;
		
		foreach ($this->views as $view){
			$view->display();
		}
	}
}