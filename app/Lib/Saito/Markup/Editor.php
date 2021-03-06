<?php

	namespace Saito\Markup;

	class Editor {

		protected $_Helper;

		public function __construct(\Helper $Helper) {
			$this->_Helper = $Helper;
		}

		/**
		 * @return string HTML-escaped content
		 */
		public function getEditorHelp() {
			return '';
		}

		/**
		 * @return array
		 */
		public function getMarkupSet() {
			return [];
		}

	}
