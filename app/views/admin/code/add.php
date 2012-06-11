<? tpl_header(); ?>
<h1>Add Code Snippet</h1>
<?= form_open('admin/' . CURRENT_PATH, 'enctype="multipart/form-data"', array('do' => 'add', 'MAX_FILE_SIZE' => (1024*1024*5))); ?>
<?= form_errors($errors); ?>
<div class="formPanel">
	<label for="title">Title</label>
	<div><input type="text" id="title" name="title" value="<?= $title ?>" /></div>
</div>
<div class="formPanel">
	<label for="category_id">Category</label>
	<div>
		<select id="category_id" name="category_id">
		<? foreach ($this->Categories->get(null, 'name ASC') as $idx =>$c): ?>
			<option<?= ($category_id == $c['id']) ? ' SELECTED' : '' ?> value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
		<? endforeach; ?>
		</select>
	</div>
</div>
<div class="formPanel">
	<label for="hl_lang">Highlight Language</label>
	<div>
		<select id="hl_lang" name="hl_lang">
		<? foreach ($highlighters as $idx =>$h): ?>
			<option<?= ($hl_lang == $h) ? ' SELECTED' : '' ?> value="<?= $h ?>"><?= $h ?></option>
		<? endforeach; ?>
		</select>
	</div>
</div>
<div class="formPanel">
	<label for="file_ext">File Extension (For Downloads,  No .)</label>
	<input type="text" id="file_ext" name="file_ext" value="<?= $file_ext ?>" style='width: 50px;' />
</div>
<div class="formPanel">
	<label for="desc">Description</label>
	<div><textarea id="desc" name="desc"><?= $desc ?></textarea></div>
</div>
<div class="formPanelExt">
	<label for="code">Code</label>
		<input<?= ($uploadCode ? ' CHECKED' : ''); ?> id="upld_1" name="uploadCode" type="radio" value="1" />
		<label for="upld_1">Upload</label>
		<input<?= (!$uploadCode ? ' CHECKED' : ''); ?> id="upld_0" name="uploadCode" type="radio" value="0" />
		<label for="upld_0">Copy and paste</label>
	<div id="codeUpld"<?= ($uploadCode ? '' : ' style="display: none;"'); ?>><input type="file" name="src" id="codeFile" /></div>
	<div id="codeArea"<?= (!$uploadCode ? '' : ' style="display: none;"'); ?>><textarea id="code" name="code" rows="10" cols="40" style='width: 900px; height: 300px;'><?= $code ?></textarea></div>
</div>
<div class="formPanelExt">
	<label for="doesExpire1">Expires?</label>
		<input<?= ($doesExpire ? ' CHECKED' : ''); ?> id="doesExpire1" name="doesExpire" type="radio" value="1" />
		<label for="doesExpire1">Yes</label>
		<input<?= (!$doesExpire ? ' CHECKED' : ''); ?> id="doesExpire0" name="doesExpire" type="radio" value="0" />
		<label for="doesExpire0">No</label>
	<div id="expContainer"<?= ($doesExpire ? '' : ' style="display: none;"'); ?>><input type="text" name="expires" id="expires" value="<?= $expires ?>" /></div>
</div>
<div class="formPanel">
	<label for="tags">Tags (Comma Seperated)</label>
	<div><textarea id="tags" name="tags"><?= $tags ?></textarea></div>
</div>
<div class='formBtnsPanel'>
	<input type='button' value="Cancel" onclick="location.href='<?= site_url() . 'admin/' . CURRENT_CONTROLLER . '/' ?>';" />
	<input type="submit" value="Add Code Snippet" />
</div>
<?= form_close(); ?>
<? tpl_footer(); ?>
