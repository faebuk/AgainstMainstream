<!-- Hier wir ein Video anhanden von Parametern generiert -->
	<div class="video">
		<section class="videosec">
			<table>
				<tr>
					<td class="videotd">
						<a href="/Default/details/<?php echo $videoid?>"><img class="postimg" alt="videoscreen" src="<?php echo $thumbnail?>"></a>
					</td>
					<td class="videocontent">
						<h2 class="videotitle"><?php echo $title?></h2>
						<p>Views: <?php echo $views?></p>
						<p>Duration: <?php echo $duration?></p>
						<div class="viewrating<?php if(isset($zusatz)){echo $zusatz;};?>" data-score="<?php echo $bewertung?>">Average rating: </div>
						<a href="http://youtube.com/watch?v=<?php echo $videoid?>">Youtube Link</a>
					</td>
				</tr>
			</table>
		</section>
	</div>
	
	