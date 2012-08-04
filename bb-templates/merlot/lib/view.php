<?php

function merlot_views_tabs() {
    echo '<div class="tabbable">';
    echo '<ul class="nav nav-tabs nav-stacked">';
    foreach (bb_get_views() as $the_view => $title) {
        $class = '';
        if (is_view() && get_view_name() == get_view_name($the_view))
            $class = 'class="active"';
            
        printf('<li %s><a href="%s">%s</a></li>', $class, get_view_link($the_view), get_view_name($the_view));
    }
    echo '</ul>';
    echo '</div>';
}

function merlot_view_breadcrumb() {
    $links = array();
    $links[] = sprintf('<a href="%s">%s</a>', bb_get_option('uri'), bb_get_option('name'));
    $links[] = sprintf('<a href="#">%s</a>', __('Views'));
    $links[] = get_view_name();
    gs_breadcrumb($links);
}

function merlot_view_header() {
?>
    <h2><?php view_name(); ?></h2>
<?php
}

