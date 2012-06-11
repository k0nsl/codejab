<? tpl_header(); ?>
<? if ($total_items): ?>
<div class='viewHeader'>
	<a class='addLink' href='<?= site_url() . 'admin/' . CURRENT_CONTROLLER . '/add' ?>'>Add new code snippet</a>
	<div class='viewOpts'>
	<form id="orderByForm" name="orderByForm" action="<?= site_url() . 'admin/' . CURRENT_CONTROLLER ?>" method="POST">
		<label for="o">Sort By</label>
		<select id='o' name='o'>
			<option<?= ($o == 'id DESC') ? ' SELECTED' : ''; ?> value="id DESC">ID Descending</option>
			<option<?= ($o == 'id ASC') ? ' SELECTED' : ''; ?> value="id ASC">ID Ascending</option>
			<option<?= ($o == 'name ASC') ? ' SELECTED' : ''; ?> value='title ASC'>Title (A-Z)</option>
			<option<?= ($o == 'name DESC') ? ' SELECTED' : ''; ?> value='title DESC'>Title (Z-A)</option>
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
	<th class='primary'>Title</th>
	<th>Category</th>
	<th>HL Lang</th>
	<th>Ext</th>
	<th>Expires</th>
	<th>&nbsp;</th>
	<th class='cbox'><input id="allCheckBox" name="allCheckBox" type="checkbox" value="1" /></th>
</tr>
</thead>
<tbody>
<? foreach ($items as $idx => $item): ?>
<tr class="<?= (($idx%2) ? 'even' : 'odd'); ?>">
	<td class='primary'><?= $item['title'] ?></td>
	<td><?= $this->Categories->byId[$item['category_id']]['name'] ?></td>
	<td><?= $item['hl_lang'] ?></td>
	<td>.<?= $item['file_ext'] ?></td>
	<td><?= ($item['expires'] > 0) ? date('m/d/Y H:i:s', $item['expires']) : 'Never'; ?></td>
	<td><a href="<?= site_url() . 'admin/' . CURRENT_CONTROLLER . '/edit/' . $item['id']; ?>" title="Edit Category"><img src='<?= site_url(); ?>images/icons/script_edit.png' alt='Edit' /></a></td>
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
	<span>Displaying <strong><?= $total_items ?></strong> code snippet<?= ($total_items <> 1) ? 's' : ''; ?></span>
<? endif; ?>
&nbsp;
</div>
<div class='viewBtns'>
	<input class='delBtn' rel='users' type='submit' value='Delete Selected' />
</div>
</div>
<?= form_close(); ?>
<? else: ?>
<p style="color: #FF0000;">There are no code snippets in the system</p>
<p><a href="<?= site_url(); ?>admin/<?= CURRENT_CONTROLLER ?>/add"><img src="<?= site_url(); ?>images/icons/script_add.png" class="lico" />Add one here</a></p>
<? endif; ?>
<br class='clear' />
<? tpl_footer(); ?>