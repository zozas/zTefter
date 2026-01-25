<h1>[@label_customers]</h1>
	<div class='form-group'>
		<small>
			<b>[@label_customer_count]</b> [@label_entries]
		</small>
	</div>
	<form method='post' action='?'>
		<input type='hidden' name='action' value='customers'>
		<div class='form-group'>
			[@label_search_string]
			<br/>
			<input type='text' placeholder='[@label_search]' name='search' maxlength='40' value='' autofocus />
		</div>
		<div class='form-group'>
			<button title='[@label_search]' type='submit'>[@label_search]</button>
			&nbsp;
			<a class='button-link' title='[@label_cancel]' href='?action=customers'>[@label_cancel]</a>
		</div>			
	</form>
	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>
					[@label_name]
					&nbsp;
					<a class='button-small-link' title='[@label_sort_asc]' href='?action=customers&order=customer&order_level=ASC'>&#9660;</a>
					<a class='button-small-link' title='[@label_sort_desc]' href='?action=customers&order=customer&order_level=DESC'>&#9650;</a>
				</th>
				<th>
					[@label_contact]
					&nbsp;
					<a class='button-small-link' title='[@label_sort_asc]' href='?action=customers&order=contact&order_level=ASC'>&#9660;</a>
					<a class='button-small-link' title='[@label_sort_desc]' href='?action=customers&order=contact&order_level=DESC'>&#9650;</a>
				</th>
			</tr>
		</thead>
		<tbody>
		[@customer_list]
		</tbody>
	</table>
