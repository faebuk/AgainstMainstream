<!-- Das hier ist das ganze Suchformular -->
<div id="suche">
	<p></p>
	<form action="/Default/suche" method="POST">
	<?php if (isset($suche)):?>
		<input type="text" name="suche" <?php echo $suche == null? 'placeholder="Search for..."':'value ="'.$suche.'"'?> autofocus/>
	<?php else:?>
		<input type="text" name="suche" placeholder="Search for..." autofocus/>
	<?php endif;?>
		<input type="submit" value="Search"/>
	</form>
	<a href="/Default/erweitert">Advanced search</a>
	<p></p>
</div>	