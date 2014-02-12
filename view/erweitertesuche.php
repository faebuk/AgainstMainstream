<!-- Das Suchformular für die erweiterte Suche -->
<p>Advanced Search</p>
<form action="/Default/erweitertAnzeigen" method="POST">
	<table>
	  <tr>
		<td>
		    Genre:
		</td>
		<td>
			<select name="genre">
			<option>All</option>
			<?php if(isset($post)): ?>
				<option <?php if($post['genre']=='Dubstep'){echo "selected";}?>>Dubstep</option>
				<option <?php if($post['genre']=='Electro'){echo "selected";}?>>Electro</option>
				<option <?php if($post['genre']=='House'){echo "selected";}?>>House</option>
				<option <?php if($post['genre']=='Reggae'){echo "selected";}?>>Reggae</option>
				<option <?php if($post['genre']=='Hip-Hop'){echo "selected";}?>>Hip-Hop</option>
				<option <?php if($post['genre']=='Pop'){echo "selected";}?>>Pop</option>
				<option <?php if($post['genre']=='Rock'){echo "selected";}?>>Rock</option>
				<option <?php if($post['genre']=='Metal'){echo "selected";}?>>Metal</option>
			<?php else: ?>
				<option>Dubstep</option>
				<option>Electro</option>
				<option>House</option>
				<option>Reggae</option>
				<option>Hip-Hop</option>
				<option>Pop</option>
				<option>Rock</option>
				<option>Metal</option>
			<?php endif; ?>
			</select>
	    </td>
	  </tr>
	  <tr>
	    <td>
	    	Title:
	    </td>
	    <td>
	    	<?php  if(isset($post)): //TODO: placeholder mit value erstetzenm, damit es im form mitgegeben wird!!?>
	    		<input class="ts" type="text" name="titel" <?php echo $post['titel'] == null? 'placeholder="Title"':'value ="'.$post['titel'].'"'?> maxlength="45">
	    	<?php else: ?>
	    		<input class="ts" type="text" name="titel" placeholder="Title" maxlength="45">
	    	<?php endif;?>
	    </td>
	  </tr>
	  <tr>
		  <td>
		  	Views:
		  </td>
		  <td>
		  <?php  if(isset($post)): ?>
		  <input class="vs"  type="text" name="view1" <?php echo $post['view1'] == null? 'placeholder="0"':'value ="'.$post['view1'].'"'?> maxlength="6">
		  	  to 
		  	<input class="vs"  type="text" name="view2" <?php echo $post['view2'] == null? 'placeholder="1 000 000"':'value ="'.$post['view2'].'"'?> maxlength="6">
		  <?php else: ?>
		  	<input class="vs"  type="text" name="view1" placeholder="0" maxlength="6">
		  	  to 
		  	<input class="vs"  type="text" name="view2" placeholder="1 000 000" maxlength="6">
		  	<?php endif;?>
		  </td>
	  </tr>
	  <tr>
	  	<td>
	  		Time:
	  	</td>
	  	<td>
	  	<?php  if(isset($post)): ?>
	  		<input class="ds"  type="text" name="dauer1" <?php echo $post['dauer1'] == null? 'placeholder="00"':'value ="'.$post['dauer1'].'"'?> maxlength="2" minlength="2">
	  		 : 
	  		<input class="ds"  type="text" name="dauer2" <?php echo $post['dauer2'] == null? 'placeholder="00"':'value ="'.$post['dauer2'].'"'?> maxlength="2" minlength="2">
	  		 : 
	  		<input class="ds"  type="text" name="dauer3" <?php echo $post['dauer3'] == null? 'placeholder="00"':'value ="'.$post['dauer3'].'"'?> maxlength="2" minlength="2">
	  		  to  
	  		<input class="ds"  type="text" name="dauer4" <?php echo $post['dauer4'] == null? 'placeholder="99"':'value ="'.$post['dauer4'].'"'?> maxlength="2" minlength="2">
	  		 : 
	  		<input class="ds"  type="text" name="dauer5" <?php echo $post['dauer5'] == null? 'placeholder="59"':'value ="'.$post['dauer5'].'"'?> maxlength="2" minlength="2"> 
	  		 : 
	  		<input class="ds"  type="text" name="dauer6" <?php echo $post['dauer6'] == null? 'placeholder="59"':'value ="'.$post['dauer6'].'"'?>" maxlength="2" minlength="2">
	  	<?php else: ?>
	  		<input class="ds"  type="text" name="dauer1" placeholder="00" maxlength="2" minlength="2">
	  		 : 
	  		<input class="ds"  type="text" name="dauer2" placeholder="00" maxlength="2" minlength="2">
	  		 : 
	  		<input class="ds"  type="text" name="dauer3" placeholder="00" maxlength="2" minlength="2">
	  		  to  
	  		<input class="ds"  type="text" name="dauer4" placeholder="99" maxlength="2" minlength="2">
	  		 : 
	  		<input class="ds"  type="text" name="dauer5" placeholder="59" maxlength="2" minlength="2"> 
	  		 : 
	  		<input class="ds"  type="text" name="dauer6" placeholder="59" maxlength="2" minlength="2">
	  	<?php endif;?>
	  	</td>
	  </tr>
	  	<tr>
        	<td>
            	Rating:
            </td>
            <td>
            	<select name="bewertung">
              		<option>All</option>
              		
              		<?php if(isset($post)): ?>
                    <option <?php if($post['bewertung'] == "1"){echo "selected";}?>>1</option>
                    <option <?php if($post['bewertung'] == "2"){echo "selected";}?>>2</option>
                    <option <?php if($post['bewertung'] == "3"){echo "selected";}?>>3</option>
                    <option <?php if($post['bewertung'] == "4"){echo "selected";}?>>4</option>
                    <option <?php if($post['bewertung'] == "5"){echo "selected";}?>>5</option>
                    
                    <?php else: ?>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                    <?php endif; ?>
            	</select>
         	</td>
         </tr>
	  
	  <tr>
	  	<td><input type="submit" value="FIND"></td>
	  </tr>
	</table>
</form>


