<h1>[@label_register]</h1>
<form method='post' action='?'>
	<input type='hidden' name='action' value='register_activate'>
	<div class='form-group'>
		<label for='email'>[@label_email]</label>
		<input type='email' id='email' placeholder='[@label_email]' name='login_username' maxlength='30' required autofocus />
	</div>
	<div class='form-group'>
		<input type='checkbox' id='terms' name='terms' title='[@label_terms]' required/>&nbsp;[@label_terms]
		<br />
		<br />
		[@label_email_help]
	</div>
	<div class='form-group'>
		<button title='[@label_register]' type='submit'>[@label_register]</button>
		&nbsp;
		<a class='button-link' title='[@label_cancel]' href='?'>[@label_cancel]</a>
	</div>
</form>
