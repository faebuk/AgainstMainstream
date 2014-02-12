<?php
require_once('lib/View.php');
require_once('lib/Model.php');
/**
 *
 * @author Fabian, Christian und Melvin
 * Verwaltet die ganzen /User/* Seiten
 */
class UserController{
	//Placeholders
	private $model = null;
	private $liedmodel = null;
	private $modelcomment = null;
	private $views = array();
	/**
	 * Konstruktor, wird beim erstellen einer Instanz ausgeführt
	 * Erstellt header, nav und obernav. zudem werden Datenbankanbindungen erstellt
	 */
	public function __construct(){
		//singelton für mySQL erstellen und Models 
		$mysql = MySQL::getInstance(array('localhost', 'root', '6Vgx8qmW', 'amdb'));
		$this->model = new Model($mysql, 'benutzer');
		$this->liedmodel = new Model($mysql, 'lied');
		$this->modelcomment = new Model($mysql, 'kommentar');
		//Standart Teilseiten schon im Konstruktor einfügen
		$out = new View('header', array('title' => 'againstMainstream'));
		$this->views['header'] = $out;
		$view = new View('nav', array('momentan' => 'edit'));
		$this->views['nav'] = $view;
		$view = new View('oberenav');
		$this->views['oberenav'] = $view;
	}
	/**
	 * Wenn man eingeloggt ist userhome anzeigen sonst Error dass man keine Berechtigung hat
	 */
	public function index(){
		if(!empty($_SESSION['username'])){	
			$this->userhome();
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
	 * Falls ein User ein Kommentar schreiben will wird diese Funktiona ausgeführt $a ist das 3 Argument (youtubelink aus DB)
	 * @param String $a
	 */
	public function wcomment($a){
		//schauen ob es überhaupt ein referer hat 
		if (!empty($_SERVER['HTTP_REFERER'])){
			$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
		}
		else {
			$url = '/Default/index';
		}
		
		$uri = explode('/', trim($url, '/'));
		
		if(isset($uri[3])){
			$controller= $uri[3];
		}
		else {
			$controller = '';
		}
		if(isset($uri[4])){
			$function= $uri[4];
		}
		else {
			$function = '';
		}
		if ($controller == 'Default' && $function == 'details'){
			
			//Überprüfen ob eingeloggt oder nicht
			if (isset($_SESSION['username'])){
				
				if (!empty($_POST['comment'])){
					//Kommentar von der POST variabel übernehmen und Username aus der session variable
					$comment = $_POST['comment'];
					$user = $_SESSION['username'];
					
					//Userid aus Datenbank holen, wird später gebraucht
					$this->model->db->query('SELECT ID_Benutzer FROM benutzer WHERE NAME = "'.$user.'";');
					$useridfetch = $this->model->fetch();
				
					//Videoid aus Datenbank holen wird später gebraucht
					$videoid = $a;
					$this->model->db->query('SELECT ID_Lied FROM lied WHERE Youtubelink = "'.$videoid.'";');
					$liedidfetch = $this->model->fetch();
					
					$benutzerid = $useridfetch[0]->ID_Benutzer;
					$liedid = $liedidfetch[0]->ID_Lied;
					
					//IDs in die Kommentartabelle einfügen
					$this->modelcomment->insert(array('Benutzer_ID' => mysql_real_escape_string($benutzerid), 'Lied_ID' => mysql_real_escape_string($liedid), 'Kommentar' => mysql_real_escape_string($comment)));
					
					//eine Success Seite displayen
					if (!empty($_SERVER['HTTP_REFERER'])){
						$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
					}
					else {
						$url = '/Default/index';
					}
					$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
					$view = new View('success', array('message' => 'Your comment has been submitted.', 'back' => $url));
					$this->views['success'] = $view;
					
				}
				else {
					//Wenn Kommentar leer ist Error ausgeben
					if (!empty($_SERVER['HTTP_REFERER'])){
						$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
					}
					else {
						$url = '/Default/index';
					}
					$view = new View('failed', array('message' => "You can't write an empty comment.", 'back' => $url));
					$this->views['failed'] = $view;
				}
			}
			//Wenn man nicht eingeloggt ist Error ausgeben
			else {
				if (!empty($_SERVER['HTTP_REFERER'])){
					$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
				}
				else {
					$url = '/Default/index';
				}
				$view = new View('failed', array('message' => "You have to be logged in to write a comment.", 'back' => $url));
				$this->views['failed'] = $view;
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
	 * Wird ausgeführt wenn man sich versucht zu registrieren
	 */
	public function registriert(){
		if (!empty($_SERVER['HTTP_REFERER'])){
			$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
		}
		else {
			$url = '/Default/index';
		}
		$uri = explode('/', trim($url, '/'));
		
		if(isset($uri[3])){
			$controller= $uri[3];
		}
		else {
			$controller = '';
		}
		if(isset($uri[4])){
			$function= $uri[4];
		}
		else {
			$function = '';
		}
		if ($controller == 'Default' && $function == 'registrierung'){
		
			$email = $_POST['email'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$password_confirm = $_POST['password_confirm'];
			//Variabeln auf diese von der $_POST übergabe setzen
			
			//Validation, ist username min.3  Zeichen
			if (strlen($username) > 2){
				//Sind die Passwörter gleich
				if ($password == $password_confirm){
					//Ist das Passwort min. 4 Zeichen
					if (strlen($password) > 3 ){
						if (filter_var($email, FILTER_VALIDATE_EMAIL)){
							//Ist Email valid?
							$email = mysql_real_escape_string($_POST['email']);
							$password = md5($password);
							//Password verschlüsseln und schauen ob der User existiert, wenn nein in Datenbank einfügen
							if(!$this->ifUserexist($email, $username, $password)){
								//Success Message einfügen
								$view = new View('success', array('message' => 'You have been registered successfully.', 'back' => '/Default/index/'));
								$this->views['success'] = $view;
							}
							//Sonst verschiedene Errors ausgeben
							else{
								if (!empty($_SERVER['HTTP_REFERER'])){
									$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
								}
								else {
									$url = '/Default/index';
								}
								$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
								$view = new View('failed', array('message' => "Username or email already exists.", 'back' => $url));
								$this->views['failed'] = $view;
							}
						}
						else {
							if (!empty($_SERVER['HTTP_REFERER'])){
								$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
							}
							else {
								$url = '/Default/index';
							}
							$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
							$view = new View('failed', array('message' => "You didn't enter a valid email adress.", 'back' => $url));
							$this->views['failed'] = $view;
						}
					}
					else {
						if (!empty($_SERVER['HTTP_REFERER'])){
							$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
						}
						else {
							$url = '/Default/index';
						}
						$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
						$view = new View('failed', array('message' => "Your password is less than 4 characters.", 'back' => $url));
						$this->views['failed'] = $view;
					}
				}
				else {
					if (!empty($_SERVER['HTTP_REFERER'])){
						$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
					}
					else {
						$url = '/Default/index';
					}
					$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
					$view = new View('failed', array('message' => "Your passwords don't match.", 'back' => $url));
					$this->views['failed'] = $view;
				}
			}
			else {
				if (!empty($_SERVER['HTTP_REFERER'])){
					$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
				}
				else {
					$url = '/Default/index';
				}
				$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
				$view = new View('failed', array('message' => "Your username is less than 3 characters.", 'back' => $url));
				$this->views['failed'] = $view;
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
	 * Beim Logout die session löschen und successmessage ausgeben
	 */
	public function logout(){
		session_destroy();

		$url = '/Default/index';
		$view = new View('success', array('message' => 'You have been succesfully logged out.', 'back' => $url));
		$this->views['success'] = $view;
	}

	/**
	 * Wird ausgeführt wenn man ein Video posten möchte
	 */
	public function post(){
		
		if (isset($_SERVER['HTTP_REFERER'])){
			//Überprüfen ob eingeloggt
			if(isset($_SESSION['username'])){
				//Variabeln aus $_POST welche übergeben wird ziehen
				$link = $_POST['yturl'];
				$genre = $_POST['genrepost'];
				
				//die URL splitten nach verschiedenen Argumenten
				parse_str( parse_url( $link, PHP_URL_QUERY ), $urlsplit);
				
				//Wenn 'v' Tag existiert (videoid) dann weiter
				if (array_key_exists('v', $urlsplit)){
					//Videoid auf 'v' Tag setzen und Anzahl Results von Youtube holen
					$videoid = $urlsplit['v'];
					$datafile = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$videoid.'&key=AIzaSyCHwZBtG3Y89NQffIXKCdlRnduFVgWR4JI&part=snippet,contentDetails,statistics,status');
					$data = json_decode($datafile);
					
					$exists = $data->{'pageInfo'}->{'totalResults'};
					
					//Schauen ob man min. 1 Resultat zurück bekommt, wenn ja weitere Sachen aus Youtube holen
					if ($exists > 0){
						//Weitere Infos wie Title etc. von Youtube holen
						$title = $data->{'items'}[0]->{'snippet'}->{'title'};
						$durationreq = $data->{'items'}[0]->{'contentDetails'}->{'duration'};
						$duration = new DateInterval($durationreq);
						$durationseconds = $duration->s+$duration->i*60+$duration->h*60*60;
						$this->liedmodel->db->query('SELECT ID_Genre FROM genre WHERE Genrename = "'.$genre.'"');
						$fetch = $this->liedmodel->fetch();
						$genreid = $fetch[0]->ID_Genre;
						
						$views = $data->{'items'}[0]->{'statistics'}->{'viewCount'};
						
						$this->liedmodel->db->query('Select * from lied where Youtubelink = "'.$videoid.'";');
						
						//Schauen ob das Video schone xistiert in der Datenbank und ob es weniger als 1mio Views hat, wenn ja dann in Datenbank einfügen
						if ($this->liedmodel->db->countRows()==0 && $views < 1000000){
							$this->liedmodel->insert(array('Youtubelink' => mysql_real_escape_string($videoid), 'Titel' => mysql_real_escape_string($title), 'Dauer' => mysql_real_escape_string($durationseconds), 'Genre_ID' => mysql_real_escape_string($genreid)));
							$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
							$view = new View('success', array('message' => 'Your Youtube video has been submitted.', 'back' => $url));
							$this->views['success'] = $view;
						}
						//error falls das Video über 1 Mio views hat
						elseif ($views > 1000000) {
							if (!empty($_SERVER['HTTP_REFERER'])){
								$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
							}
							else {
								$url = '/Default/index';
							}
							$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
							$view = new View('failed', array('message' => "Sorry, the Youtube video has over 1 million views.", 'back' => $url));
							$this->views['failed'] = $view;
						}
						//error falls Video schon in datenbank ist
						else {	
							if (!empty($_SERVER['HTTP_REFERER'])){
								$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
							}
							else {
								$url = '/Default/index';
							}			
							$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
							$view = new View('failed', array('message' => "Sorry, the Youtube video is already in the list.", 'back' => $url));
							$this->views['failed'] = $view;
						}
					}
					//falls anzahl results nicht min 1 ist ist es kein valides Video.
					else {
						if (!empty($_SERVER['HTTP_REFERER'])){
							$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
						}
						else {
							$url = '/Default/index';
						}
						$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
						$view = new View('failed', array('message' => "Sorry, this isn't a valid Youtube video.", 'back' => $url));
						$this->views['failed'] = $view;
					}
				}
				//falls kein 'v' Tag vorhanden ist
				else {
					if (!empty($_SERVER['HTTP_REFERER'])){
						$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
					}
					else {
						$url = '/Default/index';
					}
					$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
					$view = new View('failed', array('message' => "Sorry, the link you submitted is not valid.", 'back' => $url));
					$this->views['failed'] = $view;
				}
			}
			//Falls man nicht eingeloggt ist
			else{
				if (!empty($_SERVER['HTTP_REFERER'])){
					$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
				}
				else {
					$url = '/Default/index';
				}
				$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
				$view = new View('failed', array('message' => "Sorry, you have to be logged in to submit a video.", 'back' => $url));
				$this->views['failed'] = $view;
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
	 * wird bei Registrierung ausgeführt
	 * @param String $email
	 * @param String $username
	 * @param String $password
	 * @return boolean
	 */
	public function ifUserexist($email = "",$username = "",$password = ""){
		if($email == ""||$username == ""||$password == ""){
			$this->views['header']->logo = 'logo';
			$view = new View('403');
			$this->views['403'] = $view;
			unset($this->views['nav']);
			unset($this->views['oberenav']);
			$view = new View('divopen');
			$this->views['divopen'] = $view;
		}
		else{
			//Schauen bo schon ein User mit diesem Email und Password in der Datenbank existiert
			$this->model->db->query('Select * from Benutzer where email = "'.$email.'" or name like "'.$username.'";');
			//wenn keine Rückgabe dann existiert er nicht, dann in Datenbank inserten
			if($this->model->db->countRows()==0){
				$this->model->insert(array('Name' => mysql_real_escape_string($username), 'Email' => mysql_real_escape_string($email), 'Password' => $password, 'Rolle_ID' => 2));
				$this->model->db->query('Select ID_Benutzer from benutzer where name = "'.$username.'"');
				$id = $this->model->fetch();
				$id = ($id[0]->ID_Benutzer);
				return false;
			} else{
				return true;
			}
		}
		
	}
	
	/**
	 * Userhome displayen
	 */
	public function userhome(){
		if (!empty($_SESSION['username'])){
			$view = new View('userhome');
			$this->views['userhome'] = $view;
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
	 * wird ausgeführt wenn man username changen will
	 */
	public function changeusername(){
		if (!empty($_SERVER['HTTP_REFERER'])){
			$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
		}
		else {
			$url = '/Default/index';
		}
		$uri = explode('/', trim($url, '/'));
		
		if(isset($uri[3])){
			$controller= $uri[3];
		}
		else {
			$controller = '';
		}
		if(isset($uri[4])){
			$function= $uri[4];
		}
		else {
			$function = '';
		}
		if ($controller == 'User' && $function == 'userhome'){
			if (isset($_SESSION['angemeldet'])){
				$this->model->db->query('Select * from Benutzer where name = "'.$_POST['username'].'";');
				//wenn user in der datenbank mit dem Username nicht existiert dann updaten
				if($this->model->db->countRows()==0){
					if(strlen($_POST['username'])>3){
						$this->model->db->query('Update Benutzer set name = "'.mysql_real_escape_string($_POST['username']).'" where ID_Benutzer = "'.$_SESSION["id"].'";');
						$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
						$view = new View('success', array('message' => 'Your username will be changed after your next login.', 'back' => $url));
						$this->views['success'] = $view;
					}
					else{
						if (!empty($_SERVER['HTTP_REFERER'])){
							$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
						}
						else {
							$url = '/Default/index';
						}
						$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
						$view = new View('failed', array('message' => "Username is too short.", 'back' => $url));
						$this->views['failed'] = $view;
					}
				}else{
					if (!empty($_SERVER['HTTP_REFERER'])){
						$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
					}
					else {
						$url = '/Default/index';
					}
					//sonst ist er schon vergeben, also Error ausgeben
					$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
					$view = new View('failed', array('message' => "This username is already in use, please choose another one", 'back' => $url));
					$this->views['failed'] = $view;
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
	 * wird ausgeführt wenn man password changen will
	 */
	public function changepassword(){
		if (!empty($_SERVER['HTTP_REFERER'])){
			$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
		}
		else {
			$url = '/Default/index';
		}
		$uri = explode('/', trim($url, '/'));
		
		if(isset($uri[3])){
			$controller= $uri[3];
		}
		else {
			$controller = '';
		}
		if(isset($uri[4])){
			$function= $uri[4];
		}
		else {
			$function = '';
		}
		if ($controller == 'User' && $function == 'userhome'){
			//password vom aktuellen Benutzer holen
			$this->model->db->query('Select password from Benutzer where ID_Benutzer = "'.$_SESSION['id'].'";');
			$pw = $this->model->fetch();
			$pw = ($pw[0]->password);
			//Falls Eingabe von old pw gleich ist wie das was man eingibt beim Formular
			if($pw == md5($_POST['oldpw'])){
				if($_POST['newpw']==$_POST['password_confirm']){
					if(strlen($_POST['newpw'])>3){
						$this->model->db->query('Update Benutzer set password = "'.md5($_POST['newpw']).'" where ID_Benutzer = "'.$_SESSION["id"].'";');
						$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
						$view = new View('success', array('message' => 'Your password will be changed on next login.', 'back' => $url));
						$this->views['success'] = $view;
					}
					else{
						if (!empty($_SERVER['HTTP_REFERER'])){
							$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
						}
						else {
							$url = '/Default/index';
						}
						$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
						$view = new View('failed', array('message' => "Password is too short.", 'back' => $url));
						$this->views['failed'] = $view;
					}
				}
				else{
					if (!empty($_SERVER['HTTP_REFERER'])){
						$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
					}
					else {
						$url = '/Default/index';
					}
					$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
					$view = new View('failed', array('message' => "Passwords arn't the same.", 'back' => $url));
					$this->views['failed'] = $view;
				}
			}else{
				if (!empty($_SERVER['HTTP_REFERER'])){
					$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
				}
				else {
					$url = '/Default/index';
				}
				//Sonst error
				$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
				$view = new View('failed', array('message' => "Your old password is wrong.", 'back' => $url));
				$this->views['failed'] = $view;
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
	 * alle view aus views displayen
	 */
	public function __destruct(){
		$out = new View('footer');
		$this->views['footer'] = $out;
		
		foreach ($this->views as $view){
			$view->display();
		}
	}
	
	
}