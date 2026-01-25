<h1>[@label_login]</h1>
<form method='post' action='?'>
	<div class='form-group'>
		<label for='email'>[@label_email]</label>
		<input type='email' id='email' placeholder='[@label_email]' name='login_username' maxlength='30' required autofocus />
	</div>
	<div class='form-group'>
		<label for='password'>[@label_password]</label>
		<input type='password' id='password' placeholder='[@label_password]' name='login_password' maxlength='30' required />
	</div>
	<div class='form-group'>
		<button title='[@label_login]' type='submit'>[@label_login]</button>
		&nbsp;
		<a class='button-link' title='[@label_cancel]' href='?'>[@label_cancel]</a>
	</div>
	<div class='form-group'>
		<a class='button-link' title='[@label_recover]' href='?action=recover'>[@label_recover]</a>
	</div>
</form>
