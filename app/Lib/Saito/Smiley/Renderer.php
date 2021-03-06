<?php

	namespace Saito\Smiley;

	class Renderer {

		const DEBUG_SMILIES_KEY = ':smilies-debug:';

		protected $_replacements;

		/**
		 * @var \Saito\Smiley\Cache
		 */
		protected $_smileyData;

		protected $_useCache;

		public function __construct($smileyData) {
			$this->_smileyData = $smileyData;
		}

		/**
		 * Replaces all smiley-codes in a string with appropriate HTML-tags
		 *
		 * @param $string
		 * @return string
		 * @throws \RuntimeException
		 */
		public function replace($string) {
			$replacements = $this->_getReplacements();
			$string = preg_replace(
				$replacements['codes'],
				$replacements['html'],
				$string
			);
			if ($string === null) {
				throw new \RuntimeException("Can't replace smilies. 1420630983");
			}
			$string = $this->_debug($string, $replacements);
			return $string;
		}

		public function setHelper(\Helper $Helper) {
			$this->_Helper = $Helper;
		}

		/**
		 * outputs all available smilies :allSmilies:
		 *
		 * useful for debugging
		 */
		protected function _debug($string, $replacements) {
			if (strpos($string, self::DEBUG_SMILIES_KEY) === false) {
				return $string;
			}
			$smilies = $this->_smileyData->get();
			$out[] = '<table class="table table-simple">';
			$out[] = '<tr><th>Icon</th><th>Code</th><th>Image</th><th>Title</th></tr>';
			foreach ($replacements['html'] as $k => $smiley) {
				$title = $this->_l10n($smilies[$k]['title']);
				$out[] = '<tr>';
				$out[] = "<td>{$smiley}</td><td>{$smilies[$k]['code']}</td><td>{$smilies[$k]['image']}</td><td>{$title}</td>";
				$out[] = '</tr>';
			}
			$out[] = '</table>';
			return str_replace(self::DEBUG_SMILIES_KEY, implode('', $out), $string);
		}

		protected function _getReplacements() {
			if (!$this->_replacements && $this->_useCache) {
				$this->_replacements = Cache::read('Saito.Smilies.html');
			}

			if (!$this->_replacements) {
				$this->_replacements = ['codes' => [], 'html' => []];
				$this->_addSmilies($this->_replacements);
				$this->_addAdditionalButtons($this->_replacements);
				$this->_pregQuote($this->_replacements['codes']);

				if ($this->_useCache) {
					Cache::write('Saito.Smilies.html', $this->_replacements);
				}
			}

			return $this->_replacements;
		}

		/**
		 * prepares an array with smiliey-codes to be used in a preg_replace
		 *
		 * @param array $codes
		 */
		protected function _pregQuote(array &$codes) {
			$delimiter = '/';
			foreach ($codes as $key => $code) {
				$codes[$key] = $delimiter .
					// a smiley can't be concatenated to a string and requires a
					// whitespace in front
					'(^|(?<=(\s)))' .
					preg_quote($code, $delimiter) .
					$delimiter;
			}
		}

		protected function _addSmilies(&$replacements) {
			$smilies = $this->_smileyData->get();
			foreach ($smilies as $k => $smiley) {
				$replacements['codes'][] = $smiley['code'];
				$title = $this->_l10n($smiley['title']);

				//= vector font smileys
				if ($smiley['type'] === 'font') {
					$replacements['html'][$k] = $this->_Helper->Html->tag(
						'i',
						'',
						[
							'class' => "saito-smiley-font saito-smiley-{$smiley['image']}",
							'title' => $title
						]
					);
					//= pixel image smileys
				} else {
					$replacements['html'][$k] = $this->_Helper->Html->image(
						'smilies/' . $smiley['image'],
						[
							'alt' => $smiley['code'],
							'class' => 'saito-smiley-image',
							'title' => $title
						]
					);
				}
			}
		}

		protected function _l10n($string) {
			return __d('nondynamic', $string);
		}

		/**
		 * Adds additional buttons from global config
		 *
		 * @param $replacements
		 */
		protected function _addAdditionalButtons(&$replacements) {
			$additionalButtons = $this->_smileyData->getAdditionalSmilies();
			if (empty($additionalButtons)) {
				return;
			}
			foreach ($additionalButtons as $additionalButton) {
				// $s['codes'][] = ':gacker:';
				$replacements['codes'][] = $additionalButton['code'];
				// $s['html'][] = $this->_Helper->Html->image('smilies/gacker_large.png');
				if ($additionalButton['type'] === 'image') {
					$additionalButton['replacement'] = $this->_Helper->Html->image(
						'markitup' . DS . $additionalButton['replacement'],
						['class' => 'saito-smiley-image']
					);
				}
				$replacements['html'][] = $additionalButton['replacement'];
			}
		}

	}