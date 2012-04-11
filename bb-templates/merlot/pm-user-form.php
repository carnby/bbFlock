<p>
	<?php echo __('Your message will be sent to', 'bb-pm') . ' '; the_pm_receiver_login(); ?>
	<input name="receiver_id" type="hidden" id="receiver_id" value="<?php the_pm_receiver_id(); ?>" />
</p>

<p>
	<label for="pmtitle"><?php _e('Message Title', 'bb-pm'); ?>
		<input name="pmtitle" type="text" id="pmtitle" size="50" maxlength="80" tabindex="1" value="<?php the_pm_reply_title(); ?>"/>
	</label>
</p>

<p>
	<label for="post_content"><?php _e('Message:', 'bb-pm'); ?>
		<textarea name="post_content" cols="50" rows="8" id="pm_content" tabindex="2"></textarea>
	</label>
</p>
<p class="submit">
  <input type="submit" id="pmformsub" name="Submit" value="<?php _e('Send Message', 'bb-pm'); ?> &raquo;" tabindex="4" />
</p>

<p><?php _e('Allowed markup:'); ?> <code><?php allowed_markup(); ?></code>. <br /><?php _e('You can also put code in between backtick ( <code>`</code> ) characters.'); ?></p>
