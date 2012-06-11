<? tpl_header(); ?>
<h1>Login</h1>
<?= form_open('login/', 'id="loginForm"', array('do' => 'login', 'redir' => $redir)); ?>
<? form_errors($errors); ?>
<div class="formPanel">
	<label for="username">Username</label>
	<div><input type="text" id="username" name="username" value="<?= $username ?>" /></div>
</div>
<div class="formPanel">
	<label for="password">Password</label>
	<div><input type="password" id="password" name="password" value="<?= $password ?>" /></div>
</div>
<div class='formBtnsPanel'>
	<input type="submit" value="Login" />
</div>
<?= form_close(); ?>
<? tpl_footer(); ?>
