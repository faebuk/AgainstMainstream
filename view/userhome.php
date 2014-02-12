<!-- Die ganze userverwaltung -->
<h2>Userhome</h2>

<div>
       <span class="userhomeitem">Username:</span>
       <span class="userhomeitem"><?php echo $_SESSION['username']?></span>
       <a class="userhomeitem" href="#" id="addusername">Change</a>
</div>
<div id="usernamechange">
       <form id="userhomeusername" action="/User/changeusername/" method="post">
             <input type="text" name="username" class="required inputd" maxlength="45" minlength="3" placeholder="Your new username...">
             <input type="submit" value="Change">
       </form>
</div>


<div>
       <span>Password:</span> 
       <a href="#" id="addpassword">Change</a>
</div>
<div class="passwordchange">
       <form id="userhomepw" action="/User/changepassword/" method="post">
             <input type="password" name="oldpw" class="required inputd" maxlength="45" minlength="4" placeholder="Your old password..."><br>
             <input type="password" name="newpw" class="required inputd pwnorm" maxlength="45" minlength="4" placeholder="Your new password..."><br>
             <input type="password" name="password_confirm" class="required inputd" maxlength="45" minlength="4" equalTo=".pwnorm" placeholder="Confirm password..."><br>
             <input type="submit" value="Change">
       </form>
</div>
