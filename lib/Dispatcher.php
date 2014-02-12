<?php 
class Dispatcher{
	//Funktion zu dispatchen, die session wird hier gestartet dass sie auf jeder Seite verfügbar ist
	public static function dispatch(){
		session_start();
		
		//Die verschiedenen Teile der URI splitten
		$url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
		
		//Wenn erste Stelle existiert dann diesen Controller setzen sonst den default
		$controller = !empty($url[0]) ? $url[0] . "Controller" : "DefaultController";
		//Wenn zweite Stelle leer ist die Methode für den Controller index aufrüfen, sonst was in der URI steht
		$method = !empty($url[1]) ? $url[1] : 'index';
		//Das 3 Argumente (Zusatzargument) abspeichern
		$arg1 = !empty($url[2]) ? $url[2] : '';
		//Wenn der Controller existiert eine neue Instanz erstellen und die Funktion von oben aufrufen, ggf. mit den Parameter falls vorhanden
		if(file_exists('controller/'.$controller.'.php')){
			require_once ('controller/'.$controller.'.php');
			$cont = new $controller();
			if (method_exists($cont, $method)) {
				$cont->$method($arg1);
			} else {
				$cont->index($arg1);
			}
			//Controller wieder unsetten/Instanz löschen so wird der dekonstruktor ausgeführt
			unset($cont);
		} else {
			//falls der Controller nicht existiert defaultcontroller erstellen und die Funktion show404 anzeigen und Instanz löschen
			require_once ('controller/DefaultController.php');
			$cont = new DefaultController(true);
			$cont->show404();
			unset($cont);
		}
	}
}