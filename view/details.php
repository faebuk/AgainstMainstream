<!-- hier wird das ganze video angezeigt (hier kann es angeschaut werden) -->
		<section class="videodetails">
			<h2 class="videotitle"><?php echo $title?></h2>
			<table>
				<tr>
					<td class="videotd">
						<iframe width="560" height="318" src="http://www.youtube.com/embed/<?php echo $videoid?>" frameborder="0" allowfullscreen></iframe>
					</td>
					<td class="videocontent">
						<p>Views: <?php echo $views?></p>
						<p>Duration: <?php echo $duration?></p>
						<div class="basicrating" data-score="<?php echo $userrating;?>" data-id="1">Your rating: </div>
						<div id="trufa">Please click the stars to vote</div>
					</td>
				</tr>
			</table>
		</section>