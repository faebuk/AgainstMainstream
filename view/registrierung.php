<!-- dies ist das Registrierungsformular -->
<form id="registerForm" action="/User/registriert" method="post">
	<table>
		<tr>
			<td>Username: </td>
			<td><input type="text" name="username" class="required inputd" maxlength="45" minlength="3"></td>
		</tr>
		<tr>
			<td>E-Mail: </td>
			<td><input type="text" name="email" class="required email inputd"></td>
		</tr>
		<tr>
			<td>Password: </td>
			<td><input  type="password" name="password" class="required password inputd" minlength="4"></td>
		</tr>
		<tr>
			<td>Confirm Password</td>
			<td><input type="password" name="password_confirm" class="required inputd" minlength="4" equalTo=".password"></td>
		</tr>
		<tr>
			<td><input class="btnSubmit" type="submit" value="Submit"></td>
		</tr>
	</table>
</form>
