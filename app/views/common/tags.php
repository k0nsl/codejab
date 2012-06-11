<div class='tagsBox clear' id="tags_<?= $ref_id ?>">
<? if (empty($tags)): ?>
<p><? if (!empty($label)): ?><strong><?= $label ?>:</strong>&nbsp;<? endif; ?><span id='tagContainer<?= $ref_id ?>'>
<span class='errors'>No tags to display</span>
</span>
</p>
<? else: ?>
<p><? if (!empty($label)): ?><strong><?= $label ?>:</strong>&nbsp;<? endif; ?><span id='tagContainer<?= $ref_id ?>'>
<?php 
	//arsort($tags);
	$max_size = RGNK_MAX_TAG_SIZE; // max font size in pixels
    $min_size = RGNK_MIN_TAG_SIZE; // min font size in pixels
       
    // largest and smallest array values
	$max_qty = max(array_values($tags_ct));
    $min_qty = min(array_values($tags_ct));
       
	// find the range of values
    $spread = $max_qty - $min_qty;
	if ($spread == 0) { // we don't want to divide by zero
    	$spread = 1;
	}
       
    // set the font-size increment
	$step = ($max_size - $min_size) / ($spread);
?>
<? foreach ($tags as $idx => $t): ?>
<?php 
	$value = $tags_ct[$t['tag']];
	$size = round($min_size + (($value - $min_qty) * $step));
?>
<span id='tag_<?= $t['id'] ?>' class='tag'><? if ($canDelete) { ?><a href='<?= $t['id'] ?>' rel='<? $ref_id ?>' class='deleteTagLink' title='Delete Tag'><img src='<?= site_url() . 'images/icons/tag_blue_delete.png' ?>' /></a>&nbsp;<? } ?><a class='ajaxLink' style='font-size: <?= $size ?>px;' href='<?= sprintf($tagUrl, strtolower(str_replace(' ', '-', $t['tag']))); ?>'><?= $t['tag'] ?></a></span>
<? endforeach; ?>
</span>
<? endif; ?>
</div>