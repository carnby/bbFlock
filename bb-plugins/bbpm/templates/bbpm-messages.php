<?php bb_get_header(); ?>

<div class="page-header">

    <h2><?php _e( 'Private Messages', 'bbpm' ); ?> <?php if ( $get == 'page' && $action > 1 ) printf( __( '(Page %s)', 'bbpm' ), bb_number_format_i18n( $action ) ); ?></h2>

    <p><a class="btn btn-primary" href="<?php $bbpm->new_pm_link(); ?>"><?php _e( 'Send New Message &raquo;', 'bbpm' ); ?></a></p>

</div>

<table id="bbpm-message-table" class="table table-condensed table-striped table-bordered">
    <thead>
        <tr>
	        <th><?php _e( 'Subject', 'bbpm' ); ?></th>
	        <th><?php _e( 'Members', 'bbpm' ); ?></th>
	        <th><?php _e( 'Freshness' ); ?></th>
	        <th><?php _e( 'Actions', 'bbpm' ); ?></th>
        </tr>

    </thead>
    <tbody>
<?php while ( $bbpm->have_pm( bb_get_option( 'page_topics' ) * max( $action - 1, 0 ), bb_get_option( 'page_topics' ) * max( $action, 1 ) ) ) { ?>
        <tr<?php $bbpm->thread_alt_class(); ?>>
	        <td><?php if ($label = $bbpm->get_thread_label())
	            echo '<span class="label label-warning">' . $label . '</span>'; ?>
	            <a href="<?php bbpm_pm_link($bbpm->the_pm['id']); ?>"><?php echo esc_html( $bbpm->the_pm['title'] ); ?></a>
	        </td>
	        <td><?php bbpm_user_links($bbpm); ?></td>
	        <td><?php $bbpm->thread_freshness(); ?></td>
	        <td><a class="btn btn-danger" href="<?php $bbpm->thread_unsubscribe_url($bbpm->the_pm['id']); ?>"><?php _e( 'Unsubscribe', 'bbpm' ); ?></a></td>
        </tr>
<?php } ?>
    </tbody>
</table>

<?php $bbpm->pm_pages( max( $get == 'page' ? $action : 1, 1 ) ); ?>

<?php bb_get_footer(); ?>