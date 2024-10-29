<?php

namespace AutoInsertContent;

class Texts
{
	public static function getActivationNoticeText()
	{
		$url = menu_page_url(Constants::MENU_SLUG, false);
		ob_start();
		?>
		<div class="updated notice is-dismissible">
			<p><?php printf(__('activation-notice', 'auto-insert-content'), $url); ?></p>
		</div>
		<?php
		return trim(ob_get_clean());
	}

	public static function getMissingWpAutoPFilterText()
	{
		ob_start();
		?>
		<div class="updated notice is-dismissible">
			<p><?php _e('missing-wpautop-notice', 'auto-insert-content'); ?></p>
		</div>
		<?php
		return trim(ob_get_clean());
	}
}