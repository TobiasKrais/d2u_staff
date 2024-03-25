<?php

$cols = 0 === (int) 'REX_VALUE[20]' ? 8 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$position_container_classes = 'col-12 col-lg-'. $cols . $offset_lg;

$author = new TobiasKrais\D2UStaff\Staff('REX_VALUE[1]', rex_clang::getCurrentId());
$type = 'rex_mediapool_preview';

// Output
echo '<div class="'. $position_container_classes .'">';
echo '<div class="module-box-short-mod-22-3">';
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
echo \Sprog\Wildcard::get('d2u_staff_published') .' <strong>'. date('d.m.Y', rex_article::getCurrent()->getUpdateDate()) .'</strong>, '
    . \Sprog\Wildcard::get('d2u_staff_by') .' <strong>'. $link_start . $author->name . $link_end .'</strong>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
