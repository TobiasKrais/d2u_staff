<?php

$cols = 0 === (int) 'REX_VALUE[20]' ? 8 : (int) 'REX_VALUE[20]'; /** @phpstan-ignore-line */
$offset_lg = (int) 'REX_VALUE[17]' > 0 ? ' mr-lg-auto ml-lg-auto ' : ''; /** @phpstan-ignore-line */

$position_container_classes = 'col-12 col-lg-'. $cols . $offset_lg;

$type = 'REX_VALUE[1]';

$stafflist = TobiasKrais\D2UStaff\Staff::getAll(rex_clang::getCurrentId(), true);

// Output
echo '<div class="'. $position_container_classes .'">';
    echo '<div class="row d-flex align-items-stretch">'; // Flexbox-Klassen hinzugefügt
    foreach ($stafflist as $staff) {
        echo '<div class="col-12 col-md-6 module-box-wrapper-mod-22-1 d-flex">'; // Flexbox-Klasse hinzugefügt
            echo '<div class="module-box-mod-22-1 flex-fill">'; // Flexbox-Klasse hinzugefügt
                echo '<div class="row">';
                    echo '<div class="col-12 col-sm-6 col-md-4">';
                        if ($staff->article_id > 0) {
                            echo '<a href="'. rex_getUrl($staff->article_id) .'">';
                        }
                        echo '<img src="'. ('' == $staff->picture ? rex_addon::get('d2u_staff')->getAssetsUrl('noavatar.jpg') : rex_media_manager::getUrl($type, $staff->picture)) .'" alt="'. $staff->name .'">';
                        if ($staff->article_id > 0) {
                            echo '</a>';
                        }
                        echo '</div>';
                        
                        echo '<div class="col-12 col-sm-6 col-md-8">';
                        echo '<p><strong>'. $staff->name.'</strong> '. $staff->position .'<br>';
                        if ('' != $staff->area_of_responsibility) {
                            echo $staff->area_of_responsibility .'<br>';
                        }
                        echo '</p>';
                        if ('' != $staff->citation) {
                            echo TobiasKrais\D2UHelper\FrontendHelper::prepareEditorField($staff->citation);
                        }
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }
    echo '</div>';
echo '</div>';