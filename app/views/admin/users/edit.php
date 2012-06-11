<? tpl_header(); ?>
<h1>Edit User</h1>
<?= form_open('admin/' . CURRENT_PATH . '/' . $id, '', array('do' => 'update')); ?>
<?= form_errors($errors); ?>
<div class="formPanel">
	<label for="username">Username</label>
	<div><input type="text" id="username" name="username" value="<?= $username ?>" /></div>
</div>
<div class="formPanel">
	<label for="email">Email</label>
	<div><input type="text" id="email" name="email" value="<?= $email ?>" /></div>
</div>
<div>
	<label for="changePass_1">Change Password?</label>
	<input<?= ($changePass == 1) ? ' CHECKED' : ''; ?> type="radio" id="changePass_1" name="changePass" value="1" />
	<label for="changePass_1">Yes</label>
	<input<?= ($changePass == 0) ? ' CHECKED' : ''; ?> type="radio" id="changePass_0" name="changePass" value="0" />
	<label for="changePass_0">No</label>
</div>
<div id="passwordContainer"<?= (!$changePass ? ' style="display: none;"' : ''); ?>>
<div class="formPanel">
	<label for="password">Password</label>
	<div><input type="password" id="password" name="password" value="<?= $password ?>" /></div>
</div>
<div class="formPanel">
	<label for="password2">Confirm Password</label>
	<div><input type="password" id="password2" name="password2" value="<?= $password2 ?>" /></div>
</div>
</div>
<div>
	<label for="role_<?= ADMIN_ROLE ?>">Make this user an admin?</label>
	<input<?= ($role == ADMIN_ROLE) ? ' CHECKED' : ''; ?> type="radio" id="role_<?= ADMIN_ROLE ?>" name="role" value="<?= ADMIN_ROLE ?>" />
	<label for="role_<?= ADMIN_ROLE ?>">Yes</label>
	<input<?= ($role == USER_ROLE) ? ' CHECKED' : ''; ?> type="radio" id="role_<?= USER_ROLE ?>" name="role" value="<?= USER_ROLE ?>" />
	<label for="role_<?= USER_ROLE ?>">No</label>
</div>
<div>
	<label for="verified_1">This user is verified</label>
	<input<?= ($verified == 1) ? ' CHECKED' : ''; ?> type="radio" id="verified_1" name="verified" value="1" />
	<label for="verified_1">Yes</label>
	<input<?= ($verified == 0) ? ' CHECKED' : ''; ?> type="radio" id="verified_0" name="verified" value="0" />
	<label for="verified_0">No</label>
</div>
<div>
	<label for="submitter_1">This user is allowed to submit code</label>
	<input<?= ($submitter == 1) ? ' CHECKED' : ''; ?> type="radio" id="submitter_1" name="submitter" value="1" />
	<label for="submitter_1">Yes</label>
	<input<?= ($submitter == 0) ? ' CHECKED' : ''; ?> type="radio" id="submitter_0" name="submitter" value="0" />
	<label for="submitter_0">No</label>
</div>
<div class='formBtnsPanel'>
	<input type='button' value="Cancel" onclick="location.href='<?= site_url() . 'admin/' . CURRENT_CONTROLLER . '/' ?>';" />
	<input type="submit" value="Update User" />
</div>
<?= form_close(); ?>
<? tpl_footer(); ?>