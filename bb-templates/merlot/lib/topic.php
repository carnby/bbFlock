<?php


function gs_topic_labels() {
    global $topic;
    
    $labels = array();
    
    if ( '0' === $topic->topic_open )
		$labels[] = sprintf('<span class="label label-important">%s</span>', __('Closed'));
		
	if (is_front()) {
		if ( '2' === $topic->topic_sticky ) {
			$labels[] = sprintf('<span class="label label-success">%s</span>', __('Announcement'));
		}
	} else {
		if ( '1' === $topic->topic_sticky || '2' === $topic->topic_sticky ) {
			$labels[] = sprintf('<span class="label label-success">%s</span>', __('Announcement'));
		}
	}
	
	// support for unread topics
	if (!is_topic()) {
	    if (function_exists('ut_topic_has_new_posts') and ut_topic_has_new_posts($topic->topic_id)) {
	        $labels[] = sprintf('<span class="label label-warning">%s</span>', __('New'));
	    }
	}
	
	$labels = apply_filters('gs_topic_labels', $labels);
	
	if ($labels)
	    printf('%s', join('&nbsp;', $labels));
}

function gs_topic_class($classes, $topic_id) {
	global $topic;
	//$topic = get_topic($topic_id);
	$classes[] = "topic";
	$classes[] = "id-$topic->topic_id";
	
	if (!is_topic() && !is_forum()) 
		$classes[] = "forum-" . $topic->forum_id;
	if (is_bb_profile()) { 
		$classes[] = "replies";
		$classes[] = "read"; 
	}
	
	return $classes;
}


function gs_topic_loop_start($id = "latest") {
	?>
	<table id="<?php echo $id; ?>" class="forum-topics table table-condensed">
	<thead>
	    <tr>
	        <th class="span8"><?php _e('Title'); ?></th>
	        <?php if (!is_bb_profile()) { ?>
	            <th class="span3"><?php _e('Last Reply'); ?></th>
	        <?php } ?>
	        <th class="span1"><?php _e('Comments'); ?></th>
	    </tr>
	</thead>
	<tbody>
	<?php
}

function gs_topic_link() {
    if (bb_is_user_logged_in() and function_exists('ut_get_topic_unread_post_link')) {
        echo ut_get_topic_unread_post_link();
    } else {
        topic_link();
    }
}

function gs_topic_loop(&$discussions) { 
	global $topic;

    if ($discussions) {
        gs_topic_loop_start();
            
	    foreach ( $discussions as &$discussion_topic ) { 
	        $topic = $discussion_topic;
	        ?>
	        <tr <?php topic_class(); ?>>

			    <td class="topic-title">
			        
			        <h5><a href="<?php gs_topic_link(); ?>"><?php topic_title(); ?></a></h5>
			        <p><small><?php gs_topic_labels(); ?>
			        <?php if (!is_forum()) { ?>
			            <span><?php printf(_('In %s'), '<a href="' . get_forum_link($topic->forum_id) . '">' . get_forum_name($topic->forum_id) . '</a>'); ?> </span>
			        <?php } ?>
			        
			        <?php _e('Started by'); ?> <?php merlot_topic_author_profile_link(); ?>, <?php topic_start_time(); ?> <?php _e('ago'); ?>
			        </small></p>
			    </td>

                <?php if (!is_bb_profile()) { ?>
			        <td class="topic-last-post">
			            <?php gs_topic_last_poster_avatar(); ?>
			            <?php gs_topic_last_poster_profile_link(); ?>
			            <br />
			            <a href="<?php topic_last_post_link(); ?>"><?php topic_time(); ?></a>
			        </td>
			    <?php } ?>
			
			    <td class="topic-posts">
			        <?php echo human_filesize(get_topic_posts()); ?>
			    </td>
		    </tr>
	    <?php 

	    }
	    
	    gs_topic_loop_end();
	    
	    gs_discussion_pages();
	} else {
	    bb_no_discussions_message();
	}
}

function gs_discussion_pages() {
    $links = array();
    
    if (is_forum())
        $links = get_forum_pages();
    else if (is_bb_favorites())
        $links = get_favorites_pages();
    else if (is_bb_tag())
        $links = get_tag_pages();
    else if (is_view())
        $links = get_view_pages();
        
    if ($links)    
        gs_pagination_links($links);
}

function gs_topic_loop_end() {
	echo '</tbody></table>';
}

function merlot_topic_header() {
?>
    <h2 <?php topic_class('topictitle title' ); ?>><?php topic_title(); ?><?php gs_topic_labels(); ?></h2>
    <?php 
    do_action('under_title', '');
    do_action('topicmeta');
    topic_tags();
    
    merlot_topic_moderation();
}

function merlot_topic_pagination() {
    gs_pagination_links(get_topic_pages());
}

function merlot_topic_moderation() {
    if (!bb_current_user_can('moderate'))
        return;
    
    $topic = get_topic(get_topic_id(0));
    
    ?>
    <div class="well">
        <div class="row">
            <div class="span4">
                <?php
                printf('<h3>%s</h3>', __('Move Topic to Another Forum'));
                topic_move_dropdown();
                ?>
            </div>
            
            <div class="span4">
                <?php
                printf('<h3>%s</h3>', __('Add Tag'));
                tag_form();
                ?>
            </div>
            
            <div class="span3">
                <h3><?php _e('Topic Actions'); ?></h3>
                
                <?php
                topic_delete_link(array('before' => '<p>', 'after' => '</p>', 'class' => "btn btn-danger"));
	    
	            topic_sticky_link(array('before' => '<p>', 'after' => '</p>', 'class' => "btn btn-primary"));
	        
	            topic_close_link(array('before' => '<p>', 'after' => '</p>', 'class' => "btn btn-inverse"));
                ?>
            </div>
        </div>
    </div>
    <?php
    
    do_action('merlot_after_topic_moderation', $topic->topic_id);
    
}

