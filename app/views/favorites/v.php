<? tpl_header(array('PAGE_TITLE' => $snippet['title'])); ?>
<h1><?= $snippet['title'] ?></h1>
<strong>category:</strong>&nbsp;<a class='ajaxLink' href="<?= site_url(); ?>favorites/c/<?= $this->Categories->byId[$snippet['category_id']]['name'] ?>"><?= $this->Categories->byId[$snippet['category_id']]['name'] ?></a>
<strong>author:</strong>&nbsp;<? $user = $this->Users->byId($snippet['user_id']); echo $user['username']; ?>
<hr size='1' />
<p><?= $snippet['desc'] ?></p>
<h3 class='codetitle roundedtop'><?= strtolower($snippet['title']); ?></h3>
<div id="theCode">
<?= $snippet['code'] ?>
</div>
<br />
<?php echo ThumbsUp::item('snippet_' . $snippet['id'])->template(THUMBSUP_TPL); ?>
<br />
<a href="<?= site_url(); ?>favorites/d/<?= $snippet['stub'] ?>.html">Download Code</a>&nbsp;|&nbsp;<a href="<?= site_url() . 'favorites/v/' . $snippet['stub'] . '.html'; ?>" id="shareLink" title='<?= $snippet['title'] ?>'>Share Code</a><? if (is_user()): ?>&nbsp;|&nbsp;
<a href="<?= site_url(); ?>favorites/toggle/<?= $snippet['id'] ?>" class="favLink"><?= (!is_favorite($snippet['id']) ? 'Add To Favorites' : 'Remove From Favorites'); ?></a><? endif; ?>
<div id="shareBox">
	<input type='text' id='permalink' value='<?= site_url() . 'favorites/v/' . $snippet['stub'] . '.html'; ?>' />
</div>
<div id="tagsBox"><? tag_cloud(null, site_url() . 'favorites/t/%s', false, 100, 'Tags'); ?></div>
<? tpl_footer(); ?>
