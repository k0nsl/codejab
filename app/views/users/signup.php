<? tpl_header(); ?>
<h1>New User Signup</h1>
<?= form_open('signup/', 'id="sgnpForm"', array('do' => 'signup', 'redir' => $redir)); ?>
<? form_errors($errors); ?>
<div class="formPanel">
	<label for="username">Username</label>
	<div><input type="text" id="username" name="username" value="<?= $username ?>" /></div>
</div>
<div class="formPanel">
	<label for="email">Email</label>
	<div><input type="text" id="email" name="email" value="<?= $email ?>" /></div>
</div>
<div class="formPanel">
	<label for="password">Password</label>
	<div><input type="password" id="password" name="password" value="<?= $password ?>" /></div>
</div>
<div class="formPanel">
	<label for="password2">Confirm Password</label>
	<div><input type="password" id="password2" name="password2" value="<?= $password2 ?>" /></div>
</div>
<div class='formBtnsPanel'>
	<input type="submit" value="Signup" />
</div>
<?= form_close(); ?>

<? tpl_footer(); ?>