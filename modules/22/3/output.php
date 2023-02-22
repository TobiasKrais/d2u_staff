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

$author = new Staff('REX_VALUE[1]', rex_clang::getCurrentId());
$type = 'rex_mediapool_preview';

// Output
echo '<div class="'. $position_container_classes .'">';
echo '<div class="module-box-short">';
echo '<div class="row align-items-center">';
echo '<div class="col-3 col-md-2 author-image">';
$link_start = '';
$link_end = '';
if ($author->article_id > 0) {
    $link_start = '<a href="'. rex_getUrl($author->article_id) .'">';
    echo $link_start;
}
echo '<img src="'. ('' == $author->picture ? rex_addon::get('d2u_staff')->getAssetsUrl('noavatar.jpg') : 'index.php?rex_media_type='. $type .'&rex_media_file='. $author->picture) .'" alt="'. $author->name .'">';
if ($author->article_id > 0) {
    $link_end = '</a>';
    echo $link_end;
}
echo '</div>';
echo '<div class="col-9 col-md-10">';
echo '<div class="author-head">';
echo \Sprog\Wildcard::get('d2u_staff_by') .' <strong>'. $link_start . $author->name . $link_end .'</strong> '
    . \Sprog\Wildcard::get('d2u_staff_published') .' <strong>'. date('d.m.Y', rex_article::getCurrent()->getUpdateDate()) .'</strong>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
