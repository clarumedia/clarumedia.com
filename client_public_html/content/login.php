

<p class=textwarning1><?php print $strDisplayMessage?></p>

<form name="frmLogin" action="<?php print GetCurrentScript();?> " method="post">
<table class='tableForm' >
<caption>Login</caption>
<tr>
	<th>Username</th>
	<td><input type="text" name="username" value="<?php print $SREQUEST["username"];?>" required class='form-control'></td>

</tr>
<tr>
	<th>Password</th>
	<td><input type="password" name="password" required  class='form-control'></td>
</tr>
<input type=hidden name="login" value="1">

<tr valign=top>
<th></th>
<td>
<textarea name="terms-and-conditions" class='form-control' style='width:500px;height:150px;' readonly>
By logging into this system I agree to be bound by the terms and conditions of this website:
1) I am the designated assignee of the username and password.I am not using a username and password that has been assigned to somebody else.
2) I will not copy or redistribute any audio or visual material made available on this website without the express permission of the owner.
3) I understand that my actions on this website are recorded in access and database logs.
</textarea>
</td>

</tr>

<tr>

<td style='text-align:right' colspan='2'><input type="submit" name="btnlogin" value="Login"></td>
</tr>
</table>


<input type='hidden' name='utc_offset'>

<?php



if($SREQUEST) {
	while (list($key, $value) = each($SREQUEST)) {
		if ($key !="login" && $key !="username" && $key !="password"){
			print "<input type=hidden name=" . chr(34) . $key . chr(34) . " value=" . chr(34) . urlencode($value) . chr(34) . ">\n";
		}
	}
}


?>

</form>

</div>
</div>
</header>

<script type="text/javascript">

	document.frmLogin.username.focus();
	var d = new Date();

	document.frmLogin.utc_offset.value=(d.getTimezoneOffset()/60);
</script>
