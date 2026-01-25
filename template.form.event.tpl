<h1>[@label_appointment]</h1>
<h4>[@label_appointment_action]</h4>
<b>
	[@date_current]
</b>
<br />
<br />
<form method='post' action='?'>
	<input type='hidden' name='action' value='[@action]'>
	<input type='hidden' name='id' value='[@event_id]'>
	<input type='hidden' name='day' value='[@day_current]'>
	<input type='hidden' name='month' value='[@month_current]'>
	<input type='hidden' name='year' value='[@year_current]'>
	<div class='form-group'>
		&#128467; [@label_date]
		<br/>
		<input type='date' name='date' value='[@label_default_date]' [@readonly] required />
	</div>
	<div class='form-group'>
		&#9202; [@label_time]
		<br/>
		<input type='time' name='time' min='[@label_default_time_start]' step='300' max='[@label_default_time_end]' value='[@label_default_time]' [@readonly] required />
	</div>
	<div class='form-group'>
		&#9201; [@label_duration]
		<br/>
		<input type='number' name='duration' min='5' step='5' max='180' value='[@label_default_duration]' [@readonly] required />
	</div>
	<div class='form-group'>
		[@label_customer]
		<br/>
		<input type='text' placeholder='[@label_customer]' name='customer' maxlength='30' value='[@customer_value]' [@readonly] autofocus />
	</div>
	<div class='form-group'>
		[@label_contact]
		<br/>
		<input type='text' placeholder='[@label_contact]' name='contact' maxlength='30' value='[@contact_value]' [@readonly] autofocus />
	</div>
	<div class='form-group'>
		[@label_description]
		<br/>
		<input type='text' placeholder='[@label_description]' name='description' maxlength='30' value='[@description_value]' [@readonly] autofocus />
	</div>
	<div class='form-group'>
		<button title='[@label_save]' type='submit'>[@label_save]</button>
		&nbsp;
		<a class='button-link' title='[@label_cancel]' href='?'>[@label_cancel]</a>
	</div>
</form>
