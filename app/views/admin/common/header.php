<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?= SITE_NAME . ((defined(DEFAULT_PAGE_TITLE) || isset($PAGE_TITLE)) ? TITLE_DELIMITER : '') . (isset($PAGE_TITLE) ? $PAGE_TITLE : (defined(DEFAULT_PAGE_TITLE) ? DEFAULT_PAGE_TITLE : '')) ?></title>
	<? foreach ($CSS_FILES as $idx => $css_path): ?>
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>css/<?= $css_path ?>.css" />
	<? endforeach; ?>
	<? foreach ($JS_FILES as $idx => $js_path): ?>
	<script type="text/javascript" src="<?= base_url(); ?>js/<?= $js_path ?>.js"></script>
	<? endforeach; ?>
</head>
<body>
<div id="owrap">
<div id="header">
	<div class="left"><a id="logo" href="<?= site_url(); ?>admin/"><?= SITE_NAME ?> Administration</a></div>
	<div class="right">
		<a href="<?= site_url(); ?>"><img src="<?= site_url() ?>images/icons/arrow_left.png" class='lico' />Go To Site</a>&nbsp;
		<a href="<?= site_url(); ?>admin/users/edit/<?= $this->currentUser['id'] ?>"><img src="<?= site_url() ?>images/icons/lock_edit.png" class='lico' />Change Password</a>&nbsp;
		<a href="<?= site_url(); ?>admin/logout"><img src="<?= site_url() ?>images/icons/disconnect.png" class='lico' />Logout</a>	
	</div>
</div>
<div id="menu">
	<a title="Manage code snippet categories" class="roundedtop<?= (!strcmp(CURRENT_CONTROLLER, 'categories') ? ' current' : ''); ?>" href="<?= site_url(); ?>admin/categories/"><img src="<?= site_url() ?>images/icons/folder.png" class='lico' />Categories</a>
	<a title="Manage code snippets" class="roundedtop<?= (!strcmp(CURRENT_CONTROLLER, 'code') ? ' current' : ''); ?>" href="<?= site_url(); ?>admin/code/"><img src="<?= site_url() ?>images/icons/script.png" class='lico' />Code Snippets</a>
	<a title="Manage users and administrators" class="roundedtop<?= (!strcmp(CURRENT_CONTROLLER, 'users') ? ' current' : ''); ?>" href="<?= site_url(); ?>admin/users/"><img src="<?= site_url() ?>images/icons/user.png" class='lico' />Users</a>
</div>
<div id="body" class='rounded'>
<?
	$g_Feedback = $this->session->flashdata('g_Feedback');
	$b_Feedback = $this->session->flashdata('b_Feedback');
	
	if (!empty($g_Feedback)) {
	?>
	<p class='g_Feedback'><?= $g_Feedback ?></p>
	<?
	}
	
	if (!empty($b_Feedback)) {
	?>
	<p class='b_Feedback'><?= $b_Feedback ?></p>
	<?
	}
?>