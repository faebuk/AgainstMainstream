<!-- Hier wird ein neuer User generiert -->
			<tr>
				<td><?php echo $id?></td>
				<td><input type="text" name="username<?php echo $id?>" class="admintext" value="<?php echo $username?>"></td>
				<td><input type="text" name="email<?php echo $id?>" class="admintext" value="<?php echo $email?>"></td>
				<td> 
					<select name="group<?php echo $id?>" class="adminselect">
						<option>Administrator</option>
						<option <?php if($group =="User"){echo "selected";}?>>User</option>
					</select>
				</td>
				<td><input type="radio" name="<?php echo $id?>" value="edit">Edit <input type="radio" name="<?php echo $id?>" value="delete">Delete</td>
			</tr>