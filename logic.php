<?php

namespace AutoInsertContent;

class Logic
{
	public static function theContent($content)
	{
		if (get_post_type() !== 'post') {
			return $content;
		}

		$requestedPosition = get_option(Constants::OPTION_POSITION);
		$contentsToAdd = get_option(Constants::OPTION_CONTENTS);

		if (trim($contentsToAdd) === '') {
			return $content;
		}

		$contentsToAdd = wpautop(do_shortcode($contentsToAdd));

		if ($requestedPosition === '0') {
			return "{$contentsToAdd}\n\n{$content}";
		}
		if ($requestedPosition === '100') {
			return "{$content}\n\n{$contentsToAdd}";
		}
		if (!is_numeric($requestedPosition)) {
			return $content;
		}

		$requestedPosition = strlen($content) * floatval($requestedPosition) / 100;
		$tag = self::getClosestTag($requestedPosition, $content);

		if ($tag === null) {
			return $content;
		}

		$insertPoint = $tag{1} === '/' ? intval($tag[1]) + strlen($tag[0]) : $tag[1];

		return substr($content, 0, $insertPoint)
			. "\n\n{$contentsToAdd}\n\n"
			. substr($content, $insertPoint);
	}

	private static function getClosestTag($requestedPosition, $content)
	{
		preg_match_all('~<(p|div|ol|ul|/p>|/div>|/ol>|/ul>)~', $content, $matches, PREG_OFFSET_CAPTURE);

		$tagTree = $matches[0];

		$closestTag = null;
		$closestTagDistance = 9999999;
		foreach ($tagTree as $tag) {
			$tagPosition = $tag[1];
			$tagDistance = abs($requestedPosition - $tagPosition);

			if ($tagDistance < $closestTagDistance) {
				$closestTag = $tag;
				$closestTagDistance = $tagDistance;
			}
		}

		return $closestTag;
	}
}