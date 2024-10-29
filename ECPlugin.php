<?php

namespace AutoInsertContent;

class ECPlugin
{
	const OPTION_NAME = 'evidcube_aic_meta';

	const KEY_ACTIVATED = '_activated';
	const KEY_SUCCESSES = '_successes';
	const KEY_ERRORS = '_errors';

	private static $option;
	private static function getOption()
	{
		if (self::$option === null){
			self::$option = unserialize(get_option(self::OPTION_NAME, serialize([])));
			self::$option = is_array(self::$option) ? self::$option : [];
		}

		return self::$option;
	}

	private static function updateOption($value)
	{
		self::$option = $value;
		update_option(self::OPTION_NAME, serialize($value));
	}

	public static function addNotification($message, $isSuccess)
	{
		$const = $isSuccess ? self::KEY_SUCCESSES : self::KEY_ERRORS;
		$option = self::getOption();
		$option[$const] = $option[$const] ?: [];
		$option[$const][] = $message;

		self::updateOption($option);
	}

	/**
	 * @return array Two element array, first containing success notifications and the second errors
	 */
	public static function getNotifications()
	{
		$option = self::getOption();

		$result = [
			empty($option[self::KEY_SUCCESSES]) ? [] : $option[self::KEY_SUCCESSES],
			empty($option[self::KEY_ERRORS]) ? [] : $option[self::KEY_ERRORS]
		];

		$option[self::KEY_SUCCESSES] = [];
		$option[self::KEY_ERRORS] = [];
		self::updateOption($option);

		return $result;
	}

	public static function printNotifications($notifications)
	{
		foreach($notifications[0] as $text){
			echo "<div class=\"notice notice-success is-dismissible\"><p>{$text}</p></div>";
		}
		foreach($notifications[1] as $text){
			echo "<div class=\"notice notice-error is-dismissible\"><p>{$text}</p></div>";
		}
	}

	public static function triggerActivation()
	{
		$option = self::getOption();
		$option[self::KEY_ACTIVATED] = 1;
		self::updateOption($option);
	}

	public static function wasJustActivated()
	{
		$option = self::getOption();

		$wasActive = !empty($option[self::KEY_ACTIVATED]);

		$option[self::KEY_ACTIVATED] = null;
		self::updateOption($option);

		return $wasActive;
	}

	public static function registerMenu($page_title, $menu_title, $capability, $menu_slug, $template_path)
	{
		add_options_page($page_title, $menu_title, $capability, $menu_slug, function () use ($template_path){
			include $template_path;
		});
	}
}