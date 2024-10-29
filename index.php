<?php
/*
 * Plugin Name: Auto Insert Content
 * Description: Automatically inserts predefined content into each post
 * Author: Maurycy Zarzycki Evidently Cube
 * Version: 0.1
 * Requires PHP: 5.6
 * Requires at least: 4.6
 * Text Domain: auto-insert-content
 * Domain path: /i18n
 */


use AutoInsertContent\Constants;
use AutoInsertContent\ECPlugin;
use AutoInsertContent\Logic;
use AutoInsertContent\Texts;

require_once __DIR__ . '/ECPlugin.php';
require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/logic.php';
require_once __DIR__ . '/texts.php';

function _aic_activate_hook()
{
	AutoInsertContentPlugin::activateHook();
}

function _aic_uninstall_hook()
{
	AutoInsertContentPlugin::uninstallHook();
}

class AutoInsertContentPlugin
{
	public static function activateHook()
	{
		ECPlugin::triggerActivation();
	}

	public static function uninstallHook()
	{
		delete_option(Constants::OPTION_CONTENTS);
		delete_option(Constants::OPTION_POSITION);
	}

	public static function adminNotices()
	{
		if (ECPlugin::wasJustActivated()) {
			echo Texts::getActivationNoticeText();
		}

		ECPlugin::printNotifications(ECPlugin::getNotifications());

		if (isset($_GET['page']) && $_GET['page'] === 'auto-insert-content' && !has_filter('the_content', 'wpautop')){
			echo Texts::getMissingWpAutoPFilterText();
		}
	}

	public static function loadPluginTextDomain()
	{
		load_plugin_textdomain('auto-insert-content', false, basename(__DIR__) . '/i18n/');
	}

	public static function init()
	{
		add_action('admin_notices', [self::class, 'adminNotices']);
		add_action('plugins_loaded', [self::class, 'loadPluginTextDomain']);
		add_action('the_content', [Logic::class, 'theContent'], 999);

		add_action('admin_post_aic_options_update', [self::class, 'handleActionOptionsUpdate']);

		register_activation_hook(__FILE__, '_aic_activate_hook');
		register_uninstall_hook(__FILE__, '_aic_uninstall_hook');

		add_action('admin_menu', function () {
			ECPlugin::registerMenu(
				'Auto insert content',
				'Auto insert content',
				'activate_plugins',
				Constants::MENU_SLUG,
				__DIR__ . '/templates/admin-view.php'
			);
		});
	}

	public static function handleActionOptionsUpdate()
	{
		$url = admin_url('options-general.php?page=auto-insert-content');

		if (!current_user_can('activate_plugins')) {
			wp_redirect($url);
			exit;
		}

		if (isset($_POST[Constants::OPTION_CONTENTS])) {
			update_option(Constants::OPTION_CONTENTS, stripslashes($_POST[Constants::OPTION_CONTENTS]));
		}

		if (isset($_POST[Constants::OPTION_POSITION])) {
			$position = $_POST[Constants::OPTION_POSITION];

			if (!is_numeric($position)) {
				ECPlugin::addNotification(__('Position must be a number.', 'auto-insert-content'), false);

			} else if ($position < 0) {
				ECPlugin::addNotification(__("Position can't be less than zero.", 'auto-insert-content'), false);

			} else if ($position > 100) {
				ECPlugin::addNotification(__("Position can't be more than one hundred.", 'auto-insert-content'), false);

			} else if (!preg_match('~^(100|[1-9]\d|\d)$~', $position)){
				ECPlugin::addNotification(__('Position must be an integer between 0 and 100.', 'auto-insert-content'), false);

			} else {
				update_option(Constants::OPTION_POSITION, $_POST[Constants::OPTION_POSITION]);
			}
		}

		wp_redirect($url);
		exit;
	}

	public static function onSaveOptions()
	{

	}
}

AutoInsertContentPlugin::init();