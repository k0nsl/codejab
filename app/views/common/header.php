<?php
require_once dirname(dirname(dirname(__FILE__))) . '/third_party/thumbsup/init.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?= SITE_NAME . ((defined(DEFAULT_PAGE_TITLE) || isset($PAGE_TITLE)) ? TITLE_DELIMITER : '') . (isset($PAGE_TITLE) ? $PAGE_TITLE : (defined(DEFAULT_PAGE_TITLE) ? DEFAULT_PAGE_TITLE : '')) ?></title>
	<? foreach ($CSS_FILES as $idx => $css_path): ?>
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>css/<?= $css_path ?>.css" />
	<? endforeach; ?>
	<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>css/print.css" media="print" />
	<?php echo ThumbsUp::css() ?>
	<? foreach ($JS_FILES as $idx => $js_path): ?>
	<script type="text/javascript" src="<?= base_url(); ?>js/<?= $js_path ?>.js"></script>
	<? endforeach; ?>
	<?php echo ThumbsUp::javascript() ?>
</head>
<body>
<div id="owrap">
<div id="body" class="rounded">
<div id="header">
	<a id="logo" href="<?= site_url(); ?>"><?= SITE_NAME ?></a>
	<div class='right' style='text-align: right; width: 85%;'>
	<?= form_open('code/s', 'id="searchForm"', array('do' => 'search')); ?>
	<a href="<?= site_url(); ?>">Home</a>&nbsp;|
	<a href="<?= site_url(); ?>code/azip">Download All Code</a>&nbsp;|
	<? if (!is_user()): ?>
	<a href="<?= site_url(); ?>login/">Login</a>&nbsp;|
	<a href="<?= site_url(); ?>signup/">Signup</a>&nbsp;|
	<? else: ?>
	<? if (is_admin()): ?>
	<a href="<?= site_url(); ?>admin/">Admin</a>&nbsp;|
	<? else: ?>
		<? if ($this->currentUser['submitter']): ?>
		<a href="<?= site_url(); ?>code/submit">Submit Code</a>&nbsp;|
		<? endif; ?>
		<a href="<?= site_url(); ?>favorites/">Favorites</a>&nbsp;|
	<? endif; ?>
	<a href="<?= site_url(); ?>logout/">Logout</a>&nbsp;|
	<? endif; ?>
	<a href="<?= site_url(); ?>contact/" target="_new">Contact</a>
	<input id="searchTerm" class="clearfield" name="searchTerm" type="text" value="" />
	<input type="submit" value="Search" />
	<?= form_close(); ?>
	</div>
</div>
<? if (!strcmp(CURRENT_CONTROLLER, 'favorites')): ?>
<div id="catLinks">
	<a href="<?= site_url(); ?>favorites/" class="ajaxLink rounded">ALL (<?= $totalSnippets ?>)</a>
	<? foreach ($this->Categories->byId as $catid => $c): ?>
	<a href="<?= site_url(); ?>favorites/c/<?= urlencode(strtolower($c['name'])) ?>" class="ajaxLink rounded"><?= $c['name'] . ' (' . $c['numFavs'] . ')' ?></a>
	<? endforeach; ?>
	<br class="clear" />
</div>
<? else: ?>
<div id="catLinks">
	<a href="<?= site_url(); ?>code/" class="ajaxLink rounded">ALL (<?= $totalSnippets ?>)</a>
	<? foreach ($this->Categories->byId as $catid => $c): ?>
	<a href="<?= site_url(); ?>code/c/<?= urlencode(strtolower($c['name'])) ?>" class="ajaxLink rounded"><?= $c['name'] . ' (' . $c['numSnippets'] . ')' ?></a>
	<? endforeach; ?>
	<br class="clear" />
</div>
<? endif; ?>
	<div id="content">
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
