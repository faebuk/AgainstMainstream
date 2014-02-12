<!-- Hier kann ein Komentar geschrieben werden -->
		<div class="videodetails">
			<h3>Write a comment...</h3>
			<form action="/User/wcomment/<?php echo $videoid?>" method="post">
				<textarea class="comment" name="comment" maxlength="512"></textarea>
				<input type="submit" value="Submit">
			</form>
		</div>