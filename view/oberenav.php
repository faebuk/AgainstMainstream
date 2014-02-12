<!-- Dies ist die ganze obere Navigation, welche individuell nach verschiedenen Session-Parametern anders angezeigt wird -->
<div>
	<?php if( isset($_SESSION['angemeldet']) && $_SESSION['angemeldet'] == 2){?>
	<p>Logged in as <a href="/User/userhome"><?php echo $_SESSION['username']?></a></p>
	<a href="/User/Logout" >Logout</a>
	<?php }elseif (isset($_SESSION['angemeldet']) && $_SESSION['angemeldet'] == 1){?>
	<p>Welcome back master <a href="/User/userhome"><?php echo $_SESSION['username']?></a></p>
	<a href="/User/Logout" >Logout</a>
	<a href="/Admin/index">Admincenter</a>
	<?php }else{?>
	<a href="#" data-dropdown="#dropdown-6">Login</a>
	<a href="/Default/registrierung">Sign up</a>
	<?php }?>
	<?php if(isset($error)){?>
	<div id="dropdown-6" class="dropdown dropdown-tip" style="display: block; left; 162px; top: 149px;">
	<?php }else{?>
	<div id="dropdown-6" class="dropdown dropdown-tip">
	<?php }?>
		<div class="dropdown-panel">
			<form action="/Default/login" method="post">
				<table>
					<tr>
						<td>E-Mail:</td>
						<td><input type="text" name="email"></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="password" name="password"></td>
					</tr>
					<tr>
						<td><input class="btnSubmit" type="submit" value="Login"></td>
						<?php if(isset($error)){ ?>
						<td><p href="#" id="wl">Wrong Login</p></td>
						<?php } ?>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>