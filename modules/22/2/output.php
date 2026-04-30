<?php
    $cols = 'REX_VALUE[20]';
    if ('' == $cols) {
        $cols = 8;
    }
    $offset_lg_cols = (int) 'REX_VALUE[17]';
    $offset_lg = '';
    if ($offset_lg_cols > 0) { /** @phpstan-ignore-line */
        $offset_lg = ' mr-lg-auto ml-lg-auto ';
    }

    $position_container_classes = 'col-12 col-lg-'. $cols . $offset_lg;

    $author = new TobiasKrais\D2UStaff\Staff('REX_VALUE[1]', rex_clang::getCurrentId());
    $type = 'rex_mediapool_preview';

    // Output
    echo '<div class="'. $position_container_classes .'">';
    echo '<div class="module-box-mod-22-2">';
    echo '<div class="row align-items-center">';
    echo '<div class="col-3 col-md-2 author-image">';
    $link_start = '';
    $link_end = '';
    if ($author->article_id > 0) {
        $link_start = '<a href="'. rex_escape(rex_getUrl((int) $author->article_id)) .'">';
        echo $link_start;
    }
    echo '<img src="'. ('' == $author->picture ? rex_addon::get('d2u_staff')->getAssetsUrl('noavatar.jpg') : 'index.php?rex_media_type='. rex_escape($type) .'&rex_media_file='. rex_escape((string) $author->picture)) .'" alt="'. rex_escape($author->name) .'">';
    if ($author->article_id > 0) {
        $link_end = '</a>';
        echo $link_end;
    }
    echo '</div>';
    echo '<div class="col-9 col-md-10">';
    echo '<div class="author-head">';
    echo '<strong>'. $link_start . rex_escape($author->name) . $link_end .'</strong>';
    if ('' != $author->area_of_responsibility || '' != $author->position) {
        echo '<small> - ';
        if ('' != $author->area_of_responsibility) {
            echo rex_escape($author->area_of_responsibility);
        }
        if ('' != $author->area_of_responsibility && '' != $author->position) {
            echo ' / ';
        }
        if ('' != $author->position) {
            echo rex_escape($author->position);
        }
        echo '</small>';
    }
    echo '</div>';
    if ('' != $author->citation) {
        echo TobiasKrais\D2UHelper\FrontendHelper::prepareEditorField($author->citation);
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    $article = rex_article::getCurrent();
    $current_domain_with_scheme = rtrim(rex_yrewrite::getFullPath(), '/');
?>
<script type="application/ld+json">
	{
		"@context": "https://schema.org/",
		"@type": "Article",
		"mainEntityOfPage": {
			"@type": "WebPage",
			"@id": "<?= rex_yrewrite::getFullUrlByArticleId($article->getId(), rex_clang::getCurrentId()) ?>"
		},
		"author": {
			"@type": "Person",
			"name": <?= json_encode(html_entity_decode(strip_tags('' != $author->lang_name ? $author->lang_name : $author->name)), JSON_UNESCAPED_UNICODE) ?>,
			"description": <?= json_encode(html_entity_decode(strip_tags($author->citation)), JSON_UNESCAPED_UNICODE) ?>,
		    "gender": <?= json_encode((string) $author->gender, JSON_UNESCAPED_UNICODE) ?>,
			"knowsAbout": <?= json_encode(html_entity_decode(strip_tags($author->knows_about)), JSON_UNESCAPED_UNICODE) ?>,
			"url": <?= json_encode($author->article_id > 0 ? rex_getUrl((int) $author->article_id) : $current_domain_with_scheme, JSON_UNESCAPED_UNICODE) ?>,
			"image": {
				"@type":"ImageObject",
				"url": <?= json_encode($current_domain_with_scheme . rex_url::media((string) $author->picture), JSON_UNESCAPED_UNICODE) ?>,
				"caption": <?= json_encode(html_entity_decode(strip_tags('' != $author->lang_name ? $author->lang_name : $author->name)), JSON_UNESCAPED_UNICODE) ?>
			}
		},
		<?php
        if ($author->company_id) {
            $company = new \TobiasKrais\D2UStaff\Company($author->company_id);
            if ($company->company_id) {
        ?>"publisher": {
			"@type": "Organization",
			"name": <?= json_encode(html_entity_decode($company->name), JSON_UNESCAPED_UNICODE) ?>,
			"url": <?= json_encode((string) $company->url, JSON_UNESCAPED_UNICODE) ?>,
			"logo": {
				"@type": "ImageObject",
				"url": <?= json_encode($current_domain_with_scheme . rex_url::media((string) $company->logo), JSON_UNESCAPED_UNICODE) ?>
			}
		},
		<?php
            }
        }
        ?>"headline": <?= json_encode(strip_tags($article->getName()), JSON_UNESCAPED_UNICODE) ?>,
		"description": <?= json_encode(strip_tags($article->getValue('yrewrite_description')), JSON_UNESCAPED_UNICODE) ?>,
		"image": "<?= $article->getValue('yrewrite_image') ? $current_domain_with_scheme . rex_url::media($article->getValue('yrewrite_image')) : '' ?>",
		"datePublished": "<?= date('c', $article->getCreateDate()) ?>",
		"dateModified" : "<?= date('c', $article->getUpdateDate()) ?>"
	}
</script>