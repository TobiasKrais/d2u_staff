<?php

$cols = 0 === (int) 'REX_VALUE[20]' ? 8 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$position_container_classes = 'col-12 col-lg-'. $cols . $offset_lg;

$type = 'REX_VALUE[1]';

$stafflist = Staff::getAll(rex_clang::getCurrentId(), true);

// Output
foreach ($stafflist as $staff) {
    echo '<div class="'. $position_container_classes .'">';
    echo '<div class="module-box">';
    echo '<div class="row">';
    echo '<div class="col-12 col-sm-6 col-md-4">';
    if ($staff->article_id > 0) {
        echo '<a href="'. rex_getUrl($staff->article_id) .'">';
    }
    echo '<img src="';
    if ('' == $staff->picture) {
        echo rex_addon::get('d2u_staff')->getAssetsUrl('noavatar.jpg');
    } else {
        echo 'index.php?rex_media_type='. $type .'&rex_media_file='. $staff->picture;
    }
    echo '" alt="'. $staff->name .'">';
    if ($staff->article_id > 0) {
        echo '</a>';
    }
    echo '<br><br></div>';
    echo '<div class="col-12 col-sm-6 col-md-8">';
    echo '<strong>'. $staff->name.'</strong> '. $staff->position .'<br><br>';
    if ('' != $staff->area_of_responsibility) {
        echo $staff->area_of_responsibility .'<br>';
    }
    if ('' != $staff->citation) {
        echo d2u_addon_frontend_helper::prepareEditorField($staff->citation);
    }
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
