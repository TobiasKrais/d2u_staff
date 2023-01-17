<?php
	$cols = "REX_VALUE[20]";
	if($cols == "") {
		$cols = 8;
	}
	$offset_lg_cols = intval("REX_VALUE[17]");
	$offset_lg = "";
	if($offset_lg_cols > 0) { /** @phpstan-ignore-line */
		$offset_lg = " mr-lg-auto ml-lg-auto ";
	}
	
	$position_container_classes = "col-12 col-lg-". $cols . $offset_lg;
	
	$author = new Staff("REX_VALUE[1]", rex_clang::getCurrentId());
	$type = 'rex_mediapool_preview';

	// Output
	print '<div class="'. $position_container_classes .'">';
	print '<div class="module-box">';
	print '<div class="row align-items-center">';
	print '<div class="col-3 col-md-2 author-image">';
	$link_start = '';
	$link_end = '';
	if($author->article_id > 0) {
		$link_start = '<a href="'. rex_getUrl($author->article_id) .'">';
		print $link_start;
	}
	print '<img src="'. ($author->picture == "" ? rex_addon::get('d2u_staff')->getAssetsUrl("noavatar.jpg") : 'index.php?rex_media_type='. $type .'&rex_media_file='. $author->picture) .'" alt="'. $author->name .'">';
	if($author->article_id > 0) {
		$link_end = '</a>';
		print $link_end;
	}
	print '</div>';
	print '<div class="col-9 col-md-10">';
	print '<div class="author-head">';
	print '<strong>'. $link_start . $author->name . $link_end .'</strong>';
	if($author->area_of_responsibility != "" || $author->position != "") {
		print '<small> - ';
		if($author->area_of_responsibility != "") {
			print $author->area_of_responsibility;
		}
		if($author->area_of_responsibility != "" && $author->position != "") {
			print ' / ';
		}
		if($author->position != "") {
			print $author->position;
		}
		print '</small>';
	}
	print '</div>';
	if($author->citation != "") {
		print d2u_addon_frontend_helper::prepareEditorField($author->citation);
	}
	print '</div>';
	print '</div>';
	print '</div>';
	print '</div>';
	
	$article = rex_article::getCurrent();
	$current_domain_with_scheme = rtrim(rex_yrewrite::getFullPath(), '/');
?>
<script type="application/ld+json">
	{
		"@context": "https://schema.org/",
		"@type": "Article",
		"mainEntityOfPage": {
			"@type": "WebPage",
			"@id": "<?= rex_yrewrite::getFullUrlByArticleId($article->getId(), rex_clang::getCurrentId()); ?>"
		},
		"author": {
			"@type": "Person",
			"name": "<?= addslashes(html_entity_decode(strip_tags($author->lang_name != "" ? $author->lang_name : $author->name))); ?>",
			"description": "<?= addslashes(html_entity_decode(strip_tags($author->citation))); ?>",
		    "gender": "<?= $author->gender; ?>",
			"knowsAbout": "<?= addslashes(html_entity_decode(strip_tags($author->knows_about))); ?>",
			"url": "<?= ($author->article_id > 0 ? rex_getUrl($author->article_id) : $current_domain_with_scheme); ?>",
			"image": {
				"@type":"ImageObject",
				"url":"<?= $current_domain_with_scheme . rex_url::media($author->picture); ?>",
				"caption":"<?= addslashes(html_entity_decode(strip_tags($author->lang_name != "" ? $author->lang_name : $author->name))); ?>"
			}
		},
		<?php
		if($author->company_id) {
			$company = new \D2U_Staff\Company($author->company_id);
			if($company->company_id) {
		?>"publisher": {
			"@type": "Organization",
			"name": "<?= addslashes(html_entity_decode($company->name)); ?>",
			"url": "<?= $company->url; ?>",
			"logo": {
				"@type": "ImageObject",
				"url": "<?= $current_domain_with_scheme . rex_url::media($company->logo); ?>"
			}
		},
		<?php
			}
		}
		?>"headline": "<?= addslashes(strip_tags($article->getName())); ?>",
		"description": "<?= addslashes(strip_tags($article->getValue('yrewrite_description'))); ?>",
		"image": "<?= ($article->getValue('yrewrite_image') ? $current_domain_with_scheme . rex_url::media($article->getValue('yrewrite_image')) : ''); ?>",
		"datePublished": "<?= date('c', $article->getCreateDate()); ?>",
		"dateModified" : "<?= date('c', $article->getUpdateDate()); ?>"
	}
</script>