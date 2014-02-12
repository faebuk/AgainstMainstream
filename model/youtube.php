<?php
/**
 * 
 * @author Christian Ulmann
 *
 */
class youtube{
	//placholder
	private $bewertungsmodel = null;
	/**
	 * Konstruktor
	 * Baut die DB verbindung
	 */
	public function __construct(){
		$mysql = MySQL::getInstance(array('localhost', 'root', '', 'amdb'));
		$this->bewertungsmodel = new Model($mysql, 'bewertung');
		$this->videomodel = new Model($mysql, 'lied');
	}
	/**
	 * 
	 * @param unknown $video
	 * @return array |string
	 */
	public function getParams($video){
		//hollt das ganze json file vom video
		$a = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$video->Youtubelink.'&key=AIzaSyCHwZBtG3Y89NQffIXKCdlRnduFVgWR4JI&part=snippet,contentDetails,statistics,status');
		$data = json_decode($a);
		$exists = $data->{'pageInfo'}->{'totalResults'};
		//überprüft ob das Video existiert
		if ($exists > 0) {
			//hollt die verschiedenen Parameter aus dem encodeten json file  der API
			$thumbnail = $data->{'items'}[0]->{'snippet'}->{'thumbnails'}->{'default'}->{'url'};
			$views = $data->{'items'}[0]->{'statistics'}->{'viewCount'};
			$viewsformat = number_format($views, 0, '', "'");
			$liedid = $video->ID_Lied;
			$this->bewertungsmodel->db->query('SELECT Bewertung FROM bewertung WHERE LIED_ID = '.$liedid);
			$bewertungen = $this->bewertungsmodel->fetch();
			
			//löscht das Lied aus der DB, wenn es über 1'000'000 views hat.
			if ($views > 1000000){
				$this->videomodel->db->query('DELETE FROM lied WHERE ID_Lied = '.$video->ID_Lied);
			}
			
			$fullbewertung = 0;
			$anzahlbewertungen = 0;
			//rechnet die durchschnittsbewertung aus
			foreach ($bewertungen as $bewertung){
				$fullbewertung+=$bewertung->Bewertung;
				$anzahlbewertungen++;
			}
			if ($anzahlbewertungen < 1){
				$anzahlbewertungen = 1;
			}
			$bewertung = $fullbewertung/$anzahlbewertungen;
			$bewertung = round($bewertung);
			
			return array('title' => $video->Titel, 'duration' => gmdate("H:i:s", $video->Dauer), 'videoid' => $video->Youtubelink, 'thumbnail' => $thumbnail, 'views' => $viewsformat, 'bewertung' => $bewertung);
		}
		else {
			return "The videos doesn't exist";
		}
	}
}