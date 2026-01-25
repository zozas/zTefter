<h1>[@label_account]</h1>
	<div class='form-group'>
		<small>
			[@label_email] : <i>[@user_email]</i>
			</br>
			[@label_registration_date] : <i>[@user_registration_date]</i>
		</small>
	</div>
	<details>
		<summary>
			<b>[@label_business]</b>
		</summary>
		<form method='post' action='?'>
			<br/>
			<input type='hidden' name='action' value='business_change'>
			<div class='form-group'>
				[@label_brand_name]
				<br/>
				<input type='text' placeholder='[@label_brand_name]' name='brand_name' maxlength='30' value='[@brand_name]' autofocus />
			</div>
			<div class='form-group'>
				[@label_tax_name]
				<br/>
				<input type='text' placeholder='[@label_tax_name]' name='tax_name' maxlength='30' value='[@tax_name]' autofocus />
			</div>
			<div class='form-group'>
				[@label_address_name]
				<br/>
				<input type='text' placeholder='[@label_address_name]' name='address_name' maxlength='30' value='[@address_name]' autofocus />
			</div>
			<div class='form-group'>
				[@label_phone_name]
				<br/>
				<input type='text' placeholder='[@label_phone_name]' name='phone_name' maxlength='30' value='[@phone_name]' autofocus />
			</div>
			<div class='form-group'>
				[@label_cell_name]
				<br/>
				<input type='text' placeholder='[@label_cell_name]' name='cell_name' maxlength='30' value='[@cell_name]' autofocus />
			</div>
			<div class='form-group'>
				[@label_owner_name]
				<br/>
				<input type='text' placeholder='[@label_owner_name]' name='owner_name' maxlength='30' value='[@owner_name]' autofocus />
			</div>
			<div class='form-group'>
				<button title='[@label_save]' type='submit'>[@label_save]</button>
				&nbsp;
				<a class='button-link' title='[@label_cancel]' href='?'>[@label_cancel]</a>
			</div>			
		</form>
	</details>
	<br/>
	<details>
		<summary>
			<b>[@label_preferences]</b>
		</summary>
		<form method='post' action='?'>
			<br />
			<input type='hidden' name='action' value='business_preferences'>
			<div class='form-group'>
				[@label_employees]
				<br/>
				<input type='number' name='employees' min='1' max='50' value='[@label_employees_number]' />
			</div>
			<div class='form-group'>
				&#9201; [@label_duration]
				<br/>
				<input type='number' name='duration' min='5' step='5' max='180' value='[@label_default_duration]' />
			</div>
			<div class='form-group'>
				&#9202; [@label_time_start]
				<br/>
				<input type='time' name='time_start' min='00:00' step='300' max='23:59' value='[@label_default_time_start]' />
			</div>
			<div class='form-group'>
				&#9202; [@label_time_end]
				<br/>
				<input type='time' name='time_end' min='00:00' step='300' max='23:59' value='[@label_default_time_end]' />
			</div>
			<div class='form-group'>
				<button title='[@label_save]' type='submit'>[@label_save]</button>
				&nbsp;
				<a class='button-link' title='[@label_cancel]' href='?'>[@label_cancel]</a>
			</div>
		</form>
	</details>
	<br/>
	<details>
		<summary>
			<b>[@label_password_change]</b>
		</summary>
		<form method='post' action='?'>
			<input type='hidden' name='action' value='password_change'>
			<br/>
			<div class='form-group'>
				[@label_password_old]
				<br/>
				<input type='password' placeholder='[@label_password_old]' name='login_password_old' maxlength='30' autofocus>
			</div>
			<div class='form-group'>
				[@label_password_new]
				<br/>
				<input type='password' placeholder='[@label_password_new]' name='login_password_new' maxlength='30'>
			</div>
			<div class='form-group'>
				<button title='[@label_save]' type='submit'>[@label_save]</button>
				&nbsp;
				<a class='button-link' title='[@label_cancel]' href='?'>[@label_cancel]</a>
			</div>
		</form>
	</details>
	<br/>
	<details>
		<summary>
			<b>[@label_account_delete]</b>
		</summary>
		<form method='post' action='?'>
			<br/>
			<input type='hidden' name='action' value='account_delete'>
			<div class='form-group'>
				[@label_password]
				<br/>
				<input type='password' placeholder='[@label_password]' name='login_password_verification' maxlength='30' autofocus>
			</div>
			<div class='form-group'>
				<button title='[@label_delete]' type='submit'>[@label_delete]</button>
				&nbsp;
				<a class='button-link' title='[@label_cancel]' href='?'>[@label_cancel]</a>
			</div>
		</form>
	</details>
