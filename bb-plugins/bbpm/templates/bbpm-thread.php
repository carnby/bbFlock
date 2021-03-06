<?php bb_get_header(); ?>

<?php
foreach ( $messages as $i => $the_pm ) { ?>

<div class="post" id="pm-<?php echo $the_pm->ID; ?>" <?php alt_class('bbpm', 'post bbpm-post'); ?>>
    <div class="row">
        <div class="span1">
            <div class="author_avatar">
                <?php echo bb_get_avatar($the_pm->from->ID, 64); ?>
            </div>
        </div>
        
        <div class="post_meta span8">
            <div class="post_stuff pull-left">
                    <strong><a href="<?php user_profile_link($the_pm->from->ID); ?>"><?php echo $the_pm->from->user_login; ?></a></strong> <span class="label label-info"><?php echo get_user_title($the_pm->from->ID); ?></span>
            </div>
            
            <div class="post_stuff pull-right">
                <small><?php printf( __( 'Sent %s ago', 'bbpm' ), bb_since( $the_pm->date ) ); ?> <a href="<?php echo $the_pm->read_link; ?>">#</a></small>
            </div>
   
            <div class="clearfix"></div>
	        <div class="post_text">
	            <?php echo apply_filters('post_text', apply_filters('get_post_text', $the_pm->text)); ?>
	        </div>
        </div>
    </div>
</div>


<?php } // end foreach ?>

<div id="reply-form">

    <?php do_action('pre_post_form'); ?>

    <h2><?php _e('Reply', 'bbpm'); ?></h2>
    
    <form class="postform pm-form form form-vertical" id="add-post-form" method="post" action="<?php bbpm_form_handler_url(); ?>">
        <div class="control-group">
            <label class="control-label" for="message"><?php _e('Message:', 'bbom'); ?></label>
            <div class="controls">
                <textarea name="message" cols="50" rows="12" id="message" tabindex="3" class="span8"></textarea>
            </div>
        </div>

        <div class="form-actions">
            <input class="btn btn-primary" type="submit" id="postformsub" name="Submit" value="<?php echo attribute_escape(__('Send Message &raquo;', 'bbpm')); ?>" tabindex="4" />
        </div>
        
        <?php bb_nonce_field( 'bbpm-reply-' . $action ); ?>
        <input type="hidden" value="<?php echo $action; ?>" name="thread_id" id="thread_id" />
    </form>
    
    <?php do_action('post_post_form'); ?>
</div>

<script type="text/javascript">
$('#add-post-form').on('submit', function() {
    var post_content = $.trim($('#message').val());
    if (!post_content) {
        $('#add-post-form div.control-group').addClass('error');
        return false;
    }
});
$('#message').on('keyup', function() { $('#add-post-form div.control-group').removeClass('error'); });
</script>

<?php bb_get_footer(); ?>
