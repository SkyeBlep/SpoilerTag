<?php

/**
 * Mantis Core Formatting plugin
 */
class SpoilerTagPlugin extends MantisFormattingPlugin {
	/**
	 * A method that populates the plugin information and minimum requirements.
	 * @return void
	 */
	function register() {
		$this->name = "SpoilerTag";
		$this->description = "Hide spoilers inside ||Spoiler Tags||";
		$this->page = 'config';

		$this->version = '0.1';
		$this->requires = array(
			'MantisCore' => '2.25.0',
		);

		$this->author = 'Skye';
		$this->contact = '';
		$this->url = 'https://github.com/SkyeBlep/SpoilerTag/';
	}

	/**
	 * Event hook declaration.
	 * @return array
	 */
	function hooks() {
		return array(
			'EVENT_DISPLAY_TEXT'		=> 'text',			# Text String Display
			'EVENT_DISPLAY_FORMATTED'	=> 'formatted',		# Formatted String Display
			'EVENT_DISPLAY_RSS'			=> 'rss',			# RSS String Display
			'EVENT_DISPLAY_EMAIL'		=> 'email',			# Email String Display
			'EVENT_LAYOUT_RESOURCES' => 'inject_resources'	# Custom CSS
		);
	}

	/**
	 * Plain text processing.
	 * @param string  $p_event     Event name.
	 * @param string  $p_string    Un-formatted text.
	 * @param boolean $p_multiline Multi-line text.
	 * @return string plain text
	 */
	function text( $p_event, $p_string, $p_multiline = true ) {
		return $p_string;
	}

	/**
	 * Formatted text processing.
	 * @param string  $p_event     Event name.
	 * @param string  $p_string    Un-formatted text.
	 * @param boolean $p_multiline Multi-line text.
	 * @return string formatted text
	 */
	function formatted( $p_event, $p_string, $p_multiline = true ) {

		$workString = $p_string;

		// Iterate through spoiler pipes and change them to HTML
		$insideSpoilerTag = false;
		$currentSearchPosition = 0;
		
		while ($newSearchPosition = stripos($workString, "||", $currentSearchPosition))
		{
			$beforeTag = substr($workString, 0, $newSearchPosition);
			$afterTag = substr($workString, $newSearchPosition + 2);

			// Replace
			if (!$insideSpoilerTag)
				$workString = $beforeTag . "<span class='spoiler'>" . $afterTag;
			else
				$workString = $beforeTag . "</span>" . $afterTag;

			$insideSpoilerTag = !$insideSpoilerTag;

			$currentSearchPosition = $newSearchPosition + 2;
		}


		if ($insideSpoilerTag)
			return $p_string; // Mismatched pipes, so don't try to do formatting

		return $workString;
	}

	/**
	 * RSS text processing.
	 * @param string $p_event  Event name.
	 * @param string $p_string Un-formatted text.
	 * @return string Formatted RSS text.
	 */
	function rss( $p_event, $p_string ) {
		// TODO?
		return $p_string;
	}

	/**
	 * Email text processing.
	 * @param string $p_event  Event name.
	 * @param string $p_string Un-formatted text.
	 * @return string Formatted email text
	 */
	function email( $p_event, $p_string ) {
		// TODO?
		return $p_string;
	}

	function inject_resources()
	{
		//echo '<script type="text/javascript" src="' . plugin_file("QuickSearch.js") . '"></script>' . "\n";
		echo '<link rel="stylesheet" type="text/css" href="' . plugin_file("spoiler.css") . '" />' . "\n";
	}
}