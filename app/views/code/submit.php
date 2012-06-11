<? tpl_header(); ?>
<h1>Submit Code</h1>
<?= form_open(CURRENT_PATH, '', array('do' => 'submit')); ?>
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
	<input type="text" id="file_ext" name="file_ext" value="<?= $file_ext ?>" style="width: 50px;" />
</div>
<div class="formPanel">
	<label for="desc">Description</label>
	<div><textarea id="desc" name="desc"><?= $desc ?></textarea></div>
</div>
<div class="formPanel">
	<label for="code">Code</label>
	<div><textarea id="code" name="code" rows="10" cols="40" style='width: 900px; height: 300px;'><?= $code ?></textarea></div>
</div>
<div class="formPanel">
	<label for="tags">Tags (Comma Seperated)</label>
	<div><textarea id="tags" name="tags"><?= $tags ?></textarea></div>
</div>
<div class='formBtnsPanel'>
	<input type='button' value="Cancel" onclick="location.href='<?= site_url() ?>';" />
	<input type="submit" value="Add Code Snippet" />
</div>
<?= form_close(); ?>
<? tpl_footer(); ?>