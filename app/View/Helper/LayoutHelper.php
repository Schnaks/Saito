<?php

	use Saito\User\ForumsUserInterface;

	App::uses('AppHelper', 'View/Helper');

	class LayoutHelper extends AppHelper {

		public $helpers = [
				'Html'
		];

		protected $_themeImgUrl = null;

		public function beforeRender($viewFile) {
			$this->_themeImgUrl = $this->request->webroot . 'theme' . DS . $this->theme .
					DS . Configure::read('App.imageBaseUrl');
		}

		public function beforeLayout($layoutFile) {
			if (Configure::read('debug')) {
				$stylesheets[] = 'stylesheets/cake.css';
			}
			if (!empty($stylesheets)) {
				$this->Html->css($stylesheets, null, ['inline' => false]);
			}
		}

		public function jQueryTag() {
			$url = 'dist/';
			$name = 'jquery';
			if ((int)Configure::read('debug') === 0) {
				$name = $name . '.min';
			}
			return $this->Html->script($this->Html->assetUrl($url . $name,
					['ext' => '.js', 'fullBase' => true]));
		}

		/**
		 * Output link to Android touch icon
		 */
		public function androidTouchIcon($size, array $options = []) {
			return $this->_touchIcon($size,
					$options + ['rel' => 'shortcut icon']);
		}

		/**
		 * Output link to iOS touch icon
		 */
		public function appleTouchIcon($size, array $options = []) {
			return $this->_touchIcon($size, $options);
		}

		/**
		 * Output tile info for Windows 8.
		 *
		 * @param array $size unused
		 * @param array $options
		 * - 'basename' (default: app-icon)
		 * - 'color' Background color for tile.
		 * - 'title' Title displayed on tile.
		 *
		 * @return string
		 */
		public function msTouchIcon(array $size = [], array $options = []) {
			$options += [
				'baseName' => 'app-icon',
				'color' => null,
				'title' => Configure::read('Saito.Settings.forum_name')
			];
			$html = [];
			$html[] = '<meta name="application-name" content="' . h($options['title']) . '"/>';
			if (isset($options['color'])) {
				$html[] = '<meta name="msapplication-TileColor" content="' . $options['color'] . '"/>';
			}

			$_basename = $options['baseName'];
			$url = "{$this->_themeImgUrl}{$_basename}.png";
			$html[] = '<meta name="msapplication-TileImage" content="' . $url . '"/>';
			return implode("\n", $html);
		}

		/**
		 * Output link to touch icon
		 *
		 * Files must be placed in <theme>/webroot/img/<baseName>-<size>x<size>.png
		 *
		 * @param mixed $size integer or array with integer
		 * @param array $options
		 *  `baseName` (default: 'app-icon')
		 *  `precomposed` adds '-precomposed' to baseName (default: false)
		 *  `rel` (default: 'apple-touch-icon')
		 *  `size` outputs "size"-attribute (default: true)
		 * @return string
		 */
		protected function _touchIcon($size, array $options = []) {
			if (is_array($size)) {
				$_out = '';
				foreach ($size as $s) {
					$_out .= $this->appleTouchIcon($s, $options);
				}
				return $_out;
			}

			$_defaults = [
					'baseName' => 'app-icon',
					'precomposed' => false,
					'rel' => 'apple-touch-icon',
					'size' => true
			];
			$options += $_defaults;

			$_xSize = "{$size}x{$size}";

			$_basename = $options['baseName'];
			if ($options['precomposed']) {
				$_basename .= '-precomposed';
			}
			$_filename = "{$_basename}-{$_xSize}.png";

			$url = "{$this->_themeImgUrl}{$_filename}";

			$_out = "<link rel=\"{$options['rel']}\" ";
			if ($options['size']) {
				$_out .= "size=\"{$_xSize}\" ";
			}
			$_out .= "href=\"{$url}\">";
			return $_out;
		}

		/**
		 * Generates page heading html
		 *
		 * @param string $heading
		 * @param string $tag
		 * @return string
		 */
		public function pageHeading($heading, $tag = 'h1') {
			return $this->Html->tag($tag,
					$heading,
					['class' => 'pageHeading', 'escape' => true]);
		}

		/**
		 * Generates intoText tag
		 *
		 * @param string $content
		 * @return string
		 */
		public function infoText($content) {
			return $this->Html->tag('span', $content, ['class' => 'infoText']);
		}

		public function textWithIcon($text, $icon) {
			return <<<EOF
				<i class="saito-icon fa fa-$icon"></i>
				<span class="saito-icon-text">$text</span>
EOF;
		}

		public function dropdownMenuButton(array $menuItems, array $options = []) {
			$options += ['class' => ''];
			$_divider = '<li class="dropdown-divider"></li>';
			$_menu = '';
			foreach ($menuItems as $_menuItem) {
				if ($_menuItem === 'divider') {
					$_menu .= $_divider;
				} else {
					$_menu .= "<li>$_menuItem</li>";
				}
			}
			$_id = AppHelper::tagId();
			$_button = $this->Html->tag(
					'button',
					'<i class="fa fa-wrench"></i>&nbsp;<i class="fa fa-caret-down"></i>',
					$options + [
							'escape' => false,
							'onclick' => "$(this).dropdown('attach', '#d$_id');"
					]);
			$_out = <<<EOF
				$_button
				<div id="d$_id" class="dropdown-relative dropdown dropdown-tip">
					<ul class="dropdown-menu">
							$_menu
					</ul>
				</div>
EOF;
			return $_out;
		}

		public function panelHeading($content, array $options = []) {
			$options += [
					'class' => 'panel-heading',
					'escape' => true,
					'pageHeading' => false,
					'tag' => 'h2'
			];
			if ($options['pageHeading']) {
				$options['class'] .= ' pageTitle';
				$options['tag'] = 'h1';
			}
			if (is_string($content)) {
				$content = ['middle' => $content];
			}

			if ($options['escape']) {
				foreach ($content as $k => $v) {
					$content[$k] = h($v);
				}
			}

			$content['middle'] = "<{$options['tag']}>{$content['middle']}</{$options['tag']}>";

			$options['escape'] = false;
			return $this->heading($content, $options);
		}

		public function heading($content, array $options = []) {
			$options += ['class' => '', 'escape' => true];
			if (is_string($content)) {
				$_content = ['middle' => $content];
			} else {
				$_content = $content;
			}
			$_content += ['first' => '', 'middle' => '', 'last' => ''];
			$out = '';
			foreach (['first', 'middle', 'last'] as $key) {
				$out .= '<div class="heading-3-' . $key . '">';
				$out .= $options['escape'] ? h($_content[$key]) : $_content[$key];
				$out .= '</div>';
			}
			return "<div class=\"{$options['class']} heading-3\">$out</div>";
		}

		public function linkToUserProfile($user, ForumsUserInterface $CurrentUser) {
			if ($CurrentUser->isLoggedIn()) {
				return $this->Html->link($user['username'],
						'/users/view/' . $user['id']);
			} else {
				return h($user['username']);
			}
		}

		/**
		 * creates a navigation link for the navigation bar
		 *
		 * @param $content link content
		 * @param $url link url
		 * @param array $options allows options as HtmlHelper::link
		 * 	- 'class' a CSS class to apply to the navbar item
		 * 	- 'position' [left]|center|right
		 * @return string navigation link
		 */
		public function navbarItem($content, $url, array $options = []) {
			$defaults = [
				'class' => 'navbar-item',
				'position' => 'left'
			];
			$class = '';
			if (isset($options['class'])) {
				$class = $options['class'];
				unset($options['class']);
			}
			$options += $defaults;

			$options['class'] .= " {$options['position']} $class";
			unset($class, $options['position']);

			return $this->Html->link($content, $url, $options);
		}

		public function navbarBack($url = null, $title = null, $options = []) {
			if ($title === null) {
				if ($url === null) {
					$title = __('back_to_forum_linkname');
				} else {
					$title = __('Back');
				}
			}

			if ($url === null) {
				$url = '/';
			}
			$options += ['escape' => false];
			$content = $this->textWithIcon(h($title), 'arrow-left');

			return $this->navbarItem($content, $url, $options);
		}

	}
