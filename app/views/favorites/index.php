<? tpl_header(); ?>
<h1>Latest favorite code</h1>
<? if (!empty($items)): ?>
<table class='codeTable' cellspacing='0'>
<? foreach ($items as $idx => $i): ?>
<tr class="<?= ($idx%2) ? 'even' : 'odd'; ?>">
	<td><?= $i['id'] ?></td>
	<td><a class='ajaxLink' href="<?= site_url(); ?>favorites/c/<?= strtolower($this->Categories->byId[$i['category_id']]['name']); ?>"><?= $this->Categories->byId[$i['category_id']]['name']; ?></a></td>
	<td><a class='ajaxLink' href="<?= site_url(); ?>favorites/v/<?= $i['stub'] ?>.html"><?= $i['title'] ?></a></td>
	<td><?= $i['desc'] ?></td>
</tr>
<? endforeach; ?>
</table>
<? if ($total_pages > 1): ?>
<div class="pagingLinks"><strong>Pages:</strong>&nbsp;
<? /* show a link for every page in our page range */ ?>
<? foreach (range(1, $total_pages) as $p): ?>
<? if ($page == $p): ?>
	<? /* dont link the current page, just show it as emphasized text */ ?>
	<em><?= $p ?></em>&nbsp;
<? else: ?>
	<? /* page link */ ?>
	<a class='ajaxLink' href="<?= site_url(); ?>favorites/page/<?= $p ?>"><?= $p ?></a>&nbsp;
<? endif; ?>
<? endforeach; ?>
</div>
<? endif; ?>
<? tag_cloud(null, site_url() . 'favorites/t/%s', false, 100, 'By Tag'); ?>
<? else: ?>
<p class='error'>No favorite code snippets to display</p>
<? endif; ?>
<? tpl_footer(); ?>