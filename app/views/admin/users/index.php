<? tpl_header(); ?>
<? if ($total_items): ?>
<div class='viewHeader'>
	<a class='addLink' href='<?= site_url() . 'admin/' . CURRENT_CONTROLLER . '/add' ?>'>Add new user</a>
	<div class='viewOpts'>
	<form id="orderByForm" name="orderByForm" action="<?= site_url() . 'admin/' . CURRENT_CONTROLLER ?>" method="POST">
		<label for="o">Sort By</label>
		<select id='o' name='o'>
			<option<?= ($o == 'id DESC') ? ' SELECTED' : ''; ?> value="id DESC">ID Descending</option>
			<option<?= ($o == 'id ASC') ? ' SELECTED' : ''; ?> value="id ASC">ID Ascending</option>
			<option<?= ($o == 'username ASC') ? ' SELECTED' : ''; ?> value='username ASC'>Username (A-Z)</option>
			<option<?= ($o == 'username DESC') ? ' SELECTED' : ''; ?> value='username DESC'>Username (Z-A)</option>
			<option<?= ($o == 'role DESC') ? ' SELECTED' : ''; ?> value='role DESC'>Admins First</option>
			<option<?= ($o == 'role ASC') ? ' SELECTED' : ''; ?> value='role ASC'>Admins Last</option>
			<option<?= ($o == 'last_login DESC') ? ' SELECTED' : ''; ?> value='last_login DESC'>Last Login Descending</option>
			<option<?= ($o == 'last_login ASC') ? ' SELECTED' : ''; ?> value='last_login ASC'>Last Login Ascending</option>
		</select>
		<input type="submit" value="GO" />
	</form>
	</div>
	<br class='clear' />
</div>
<?= form_open('admin/' . CURRENT_CONTROLLER . '/delete', '', array('do' => 'delete'))?>
<table cellspacing='0' class='viewTable'>
<thead>
<tr>
	<th class='primary'>Username</th>
	<th>Email</th>
	<th>Verified</th>
	<th>Submitter</th>
	<th>Is Admin</th>
	<th>Last Login</th>
	<th>&nbsp;</th>
	<th class='cbox'><input id="allCheckBox" name="allCheckBox" type="checkbox" value="1" /></th>
</tr>
</thead>
<tbody>
<? foreach ($items as $idx => $item): ?>
<tr class="<?= (($idx%2) ? 'even' : 'odd'); ?>">
	<td class='primary'><?= $item['username'] ?></td>
	<td><?= $item['email'] ?></td>
	<td><?= ($item['verified'] == 1) ? 'Yes' : 'No'; ?></td>
	<td><?= ($item['submitter'] == 1) ? 'Yes' : 'No'; ?></td>
	<td><?= ($item['role'] == ADMIN_ROLE) ? 'Yes' : 'No'; ?></td>
	<td><?= ($item['last_login'] > 0) ? date('M jS, Y h:iA', $item['last_login']) : 'Never'; ?></td>
	<td><a href="<?= site_url() . 'admin/' . CURRENT_CONTROLLER . '/edit/' . $item['id']; ?>" title="Edit User"><img src='<?= site_url(); ?>images/icons/user_edit.png' alt='Edit' /></a></td>
	<td class='cbox'><input id="item_<?= $item['id'] ?>" name="items[]" type="checkbox" value="<?= $item['id'] ?>" /></td>
</tr>
<? endforeach; ?>
</tbody>
</table>
<div class='viewFooter'>
<div class='pagingLinks'>
<? if ($total_pages > 1): ?>
	<strong>Pages:</strong>&nbsp;
	<? foreach (range(1, $total_pages) as $p): ?>
	<? if ($page == $p): ?>
	<strong><?= $p ?></strong>
	<? else: ?>
	<a href='<?= site_url() . 'admin/' . CURRENT_CONTROLLER . '/index/' . $p ?>'><?= $p ?></a>
	<? endif; ?>
	<? endforeach; ?>
<? else: ?>
	<span>Displaying <strong><?= $total_items ?></strong> user<?= ($total_items <> 1) ? 's' : ''; ?></span>
<? endif; ?>
&nbsp;
</div>
<div class='viewBtns'>
	<input class='delBtn' rel='users' type='submit' value='Delete Selected' />
</div>
</div>
<?= form_close(); ?>
<? else: ?>
<p style="color: #FF0000;">There are no users in the system!</p>
<p>This is really bad since you won't be able to login to the control panel.</p>
<p><a href="<?= site_url(); ?>admin/<?= CURRENT_CONTROLLER ?>/add"><img src="<?= site_url(); ?>images/icons/user_add.png" class="lico" />Add atleast one admin user immediately!</a></p>
<? endif; ?>
<br class='clear' />
<? tpl_footer(); ?>