<!-- generiert eine spalte mit einem video für das Admincenter und setzt die werte ein, welche übergeben werden -->
			<tr>
				<td><?php echo $id?></td>
				<td><?php echo $videoid?></td>
				<td><?php echo $duration?></td>
				<td><input type="text" name="titel<?php echo $id?>" class="admintext" value="<?php echo $title?>"></td>
				<td>
					<select name="genre<?php echo $id?>" class="adminselect">
						<option>Dubstep</option>
						<option <?php if($genre =="Electro"){echo "selected";}?>>Electro</option>
						<option <?php if($genre =="House"){echo "selected";}?>>House</option>
						<option <?php if($genre =="Reggae"){echo "selected";}?>>Reggae</option>
						<option <?php if($genre =="Hip-Hop"){echo "selected";}?>>Hip-Hop</option>
						<option <?php if($genre =="Pop"){echo "selected";}?>>Pop</option>
						<option <?php if($genre =="Rock"){echo "selected";}?>>Rock</option>
						<option <?php if($genre =="Metal"){echo "selected";}?>>Metal</option>
					</select>
				</td>
				<td><?php echo $views?></td>
				<td><?php echo $bewertung?></td>
				<td><?php echo $kommentare?></td>
				<td><input type="radio" name="<?php echo $id?>" value="edit">Edit <input type="radio" name="<?php echo $id?>" value="delete">Delete</td></td>
			</tr>
			
			