<?php use AutoInsertContent\Constants; ?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e('Auto insert content', 'auto-insert-content'); ?></h1>

	<p><?php _e('plugin-description', 'auto-insert-content'); ?></p>

	<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
		<input type="hidden" name="action" value="aic_options_update">
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<label for="<?php echo Constants::OPTION_CONTENTS; ?>">
						<?php _e('contents', 'auto-insert-content'); ?>
					</label>
				</th>
				<td>
					<textarea
							id="<?php echo Constants::OPTION_CONTENTS; ?>"
							name="<?php echo Constants::OPTION_CONTENTS; ?>"
							class="large-text code"
							rows="10"
							cols="50"
					><?php echo htmlspecialchars(get_option(Constants::OPTION_CONTENTS)); ?></textarea>
					<p class="description"><?php _e('You can use shortcodes here.', 'auto-insert-content'); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="<?php echo Constants::OPTION_POSITION; ?>">
						<?php _e('position', 'auto-insert-content'); ?>
					</label>
				</th>
				<td>
					<input
							id="<?php echo Constants::OPTION_POSITION; ?>-position"
							name="<?php echo Constants::OPTION_POSITION; ?>"
							class="ltr"
							type="number"
							min="0"
							max="100"
							step="1"
							size="4"
							value="<?php echo htmlspecialchars(get_option(Constants::OPTION_POSITION, -1)); ?>">%
					<p class="description"><?php _e('description', 'auto-insert-content'); ?></p>
				</td>
			</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
		</p>
	</form>
</div>
