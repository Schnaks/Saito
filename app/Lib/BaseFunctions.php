<?php

	/**
	 * Sets variable to value if it's undefined
	 *
	 * @param $variable
	 * @param $value
	 */
	function SDV(&$variable, $value) {
		if (!isset($variable)) {
			$variable = $value;
		}
	}

	/**
	 * returns date in SQL friendly format
	 *
	 * @param null $timestamp
	 * @return string
	 */
	function bDate($timestamp = null) {
		if ($timestamp === null) {
			$timestamp = time();
		}
		return date('Y-m-d H:i:s', $timestamp);
	}

	/**
	 * Remove and re-prepend CakePHP's autoloader as Composer thinks it is the
	 * most important.
	 *
	 * @see http://goo.gl/kKVJO7
	 */
	function Cake2ComposerAutoloadFix() {
		spl_autoload_unregister(['App', 'load']);
		spl_autoload_register(['App', 'load'], true, true);
	}
