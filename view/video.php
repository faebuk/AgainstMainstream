	<div class="video">
		<section class="videosec">
			<table>
				<tr>
					<td class="videotd">
						<?php 
						$url = "http://www.youtube.com/watch?v=Z-zL_paDVTc";	
						parse_str( parse_url( $url, PHP_URL_QUERY ), $videoid );
						$a = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$videoid['v'].'&key=AIzaSyCHwZBtG3Y89NQffIXKCdlRnduFVgWR4JI&part=snippet,contentDetails,statistics,status');
						//https://www.googleapis.com/youtube/v3/videos?id=VjYIwCLJne4&key=AIzaSyCHwZBtG3Y89NQffIXKCdlRnduFVgWR4JI&part=snippet,contentDetails,statistics,status
						$data = json_decode($a);
						$thumbnail = $data->{'items'}[0]->{'snippet'}->{'thumbnails'}->{'default'}->{'url'};
						$views = $data->{'items'}[0]->{'statistics'}->{'viewCount'};
						$viewsformat = number_format($views, 0, '', "'");
						$title = $data->{'items'}[0]->{'snippet'}->{'title'};
						$durationreq = $data->{'items'}[0]->{'contentDetails'}->{'duration'};
						$duration = new DateInterval($durationreq);
						$durationseconds = $duration->s+$duration->i*60+$duration->h*60*60;
						$durationanzeige = gmdate("H:i:s", $durationseconds);
						
						echo '<a href="/Default/'.$videoid['v'].'"><img class="postimg" alt="videoscreen" src="'.$thumbnail.'"></a>';
						?>
					</td>
				
					<td class="videocontent">
						<?php 
						echo '<h2 class="videotitle">'.$title.'</h2>';
						echo '<p>Views: '.$viewsformat.'</p>';
						echo '<p>Duration: '.$durationanzeige.'</p>';
						echo '<div class="basicrating" data-average="5" data-id="1"></div>';
						echo '<a href="http://youtube.com/watch?v='.$videoid['v'].'">Youtube Link</a>';
						?>
						
					</td>
				</tr>
			</table>
		</section>
	</div>
	<div class="video">
		<section class="videosec">
			<table>
				<tr>
					<td class="videotd">
						<?php 
						$url = "http://www.youtube.com/watch?v=VqNGgk5C7m4";	
						parse_str( parse_url( $url, PHP_URL_QUERY ), $videoid );
						$a = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$videoid['v'].'&key=AIzaSyCHwZBtG3Y89NQffIXKCdlRnduFVgWR4JI&part=snippet,contentDetails,statistics,status');
						//https://www.googleapis.com/youtube/v3/videos?id=VjYIwCLJne4&key=AIzaSyCHwZBtG3Y89NQffIXKCdlRnduFVgWR4JI&part=snippet,contentDetails,statistics,status
						$data = json_decode($a);
						$thumbnail = $data->{'items'}[0]->{'snippet'}->{'thumbnails'}->{'default'}->{'url'};
						$views = $data->{'items'}[0]->{'statistics'}->{'viewCount'};
						$viewsformat = number_format($views, 0, '', "'");
						$title = $data->{'items'}[0]->{'snippet'}->{'title'};
						$duration = $data->{'items'}[0]->{'contentDetails'}->{'duration'};
						$duration = new DateInterval($duration);
						$durationseconds = $duration->s+$duration->i*60+$duration->h*60*60;
						$durationanzeige = gmdate("H:i:s", $durationseconds);
						
						echo '<a href="/Default/'.$videoid['v'].'"><img class="postimg" alt="videoscreen" src="'.$thumbnail.'"></a>';
						?>
					</td>
				
					<td class="videocontent">
						<?php
						echo '<h2 class="videotitle">'.$title.'</h2>';
						echo '<p>Views: '.$viewsformat.'</p>';
						echo '<p>Duration: '.$durationanzeige.'</p>';
						echo '<div class="basicrating" data-average="4" data-id="1"></div>';
						echo '<a href="http://youtube.com/watch?v='.$videoid['v'].'">Youtube Link</a>';
						?>
						
					</td>
				</tr>
			</table>
		</section>
	</div>
	<div class="video">
		<section class="videosec">
			<table>
				<tr>
					<td class="videotd">
						<?php 
						$url = "http://www.youtube.com/watch?v=zNlY0rHWMEs";	
						parse_str( parse_url( $url, PHP_URL_QUERY ), $videoid );
						$a = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$videoid['v'].'&key=AIzaSyCHwZBtG3Y89NQffIXKCdlRnduFVgWR4JI&part=snippet,contentDetails,statistics,status');
						//https://www.googleapis.com/youtube/v3/videos?id=VjYIwCLJne4&key=AIzaSyCHwZBtG3Y89NQffIXKCdlRnduFVgWR4JI&part=snippet,contentDetails,statistics,status
						$data = json_decode($a);
						$thumbnail = $data->{'items'}[0]->{'snippet'}->{'thumbnails'}->{'default'}->{'url'};
						$views = $data->{'items'}[0]->{'statistics'}->{'viewCount'};
						$viewsformat = number_format($views, 0, '', "'");
						$title = $data->{'items'}[0]->{'snippet'}->{'title'};
						$duration = $data->{'items'}[0]->{'contentDetails'}->{'duration'};
						$duration = new DateInterval($duration);
						$durationseconds = $duration->s+$duration->i*60+$duration->h*60*60;
						$durationanzeige = gmdate("H:i:s", $durationseconds);;
						
						echo '<a href="/Default/'.$videoid['v'].'"><img class="postimg" alt="videoscreen" src="'.$thumbnail.'"></a>';
						?>
					</td>
				
					<td class="videocontent">
						<?php
						echo '<h2 class="videotitle">'.$title.'</h2>';
						echo '<p>Views: '.$viewsformat.'</p>';
						echo '<p>Duration: '.$durationanzeige.'</p>';
						echo '<div class="basicrating" data-average="3" data-id="1"></div>';
						echo '<a href="http://youtube.com/watch?v='.$videoid['v'].'">Youtube Link</a>';
						?>
						
					</td>
				</tr>
			</table>
		</section>
	</div>