<!-- Hier kann man videos posten -->
<div><button id="showform">Post a video...</button></div>
<div id="submitform">
	<form id="videosubmit" action="/User/post" method="post">
	<table>
		<tr>
			<td>
				<input id="urlform" type="text" placeholder="YouTube Link here" class="url required" name="yturl">
				
			</td>
		</tr>
		<tr>
			<td>
				<select id="dd1" name="genrepost" class="required">
					<option value="">Choose Genre</option>
					<option >Dubstep</option>
					<option >Electro</option>
					<option >House</option>
					<option >Reggae</option>
					<option >Hip-Hop</option>
					<option >Pop</option>
					<option >Rock</option>
					<option >Metal</option>
				</select>
			</td>
		</tr>
		<tr>
			<td id="blaj"></td>
		</tr>
		<tr>
			<td><input type="submit" value="Post"></td>
		</tr>
	</table>
	</form>
	
</div>
