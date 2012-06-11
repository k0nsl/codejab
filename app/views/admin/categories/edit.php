<? tpl_header(); ?>
<h1>Edit Category</h1>
<?= form_open('admin/' . CURRENT_PATH . '/' . $id, '', array('do' => 'update')); ?>
<?= form_errors($errors); ?>
<div class="formPanel">
	<label for="name">Name</label>
	<div><input type="text" id="name" name="name" value="<?= $name ?>" /></div>
</div>
<div class='formBtnsPanel'>
	<input type='button' value="Cancel" onclick="location.href='<?= site_url() . 'admin/' . CURRENT_CONTROLLER . '/' ?>';" />
	<input type="submit" value="Update Category" />
</div>
<?= form_close(); ?>
<? tpl_footer(); ?>