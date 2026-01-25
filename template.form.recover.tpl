<h1>[@label_recover]</h1>
<form method='post' action='?'>
	<input type='hidden' name='action' value='recover_activate'>
	<div class='form-group'>
		<label for='email'>[@label_email]</label>
		<input type='email' id='email' placeholder='[@label_email]' name='login_username' maxlength='30' required autofocus />
	</div>
	<div class='form-group'>
		[@label_email_help]
	</div>
	<div class='form-group'>
		<button title='[@label_recover]' type='submit'>[@label_recover]</button>
	</div>
	<div class='form-group'>
		<a class='button-link' title='[@label_cancel]' href='?'>[@label_cancel]</a>
	</div>
</form>
