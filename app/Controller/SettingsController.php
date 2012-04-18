<?php

	class SettingsController extends AppController {

		public $name = 'Settings';
		public $helpers = array(
				'TimeH',
		);

		/*
		 * Subset of MLF settings currently used by Saito
		 */
		protected $_currentlyUsedSettings = array(
				'autolink' => 1,
				'bbcode_img' => 1,
				'edit_delay' => 1,
				'edit_period' => 1,
				'forum_email' => 1,
				'forum_name' => 1,
				'quote_symbol' => 1,
				'signature_separator' => 1,
				'smilies' => 1,
				'subject_maxlength' => 1,
				'text_word_maxlength' => 1,
				'thread_depth_indent' => 1,
				'topics_per_page' => 1,
				'userranks_ranks' => 1,
				'userranks_show' => 1,
				'video_domains_allowed' => 1,
		);

		public function admin_index() {
			$settings = $this->request->data = $this->Setting->getSettings();
			$this->set('Settings', $settings);
			$settings = array_intersect_key($settings, $this->_currentlyUsedSettings);
			ksort($settings);
			$this->set('autoSettings', $settings);
		}

		public function admin_edit($id = NULL) {
			if ( !$id ) {
				$this->redirect(array( 'action ' => 'index' ));
			}

			$this->Setting->id = $id;

			if ( empty($this->request->data) ) {
				$this->request->data = $this->Setting->read();
				if ( empty($this->request->data) ) {
					$this->Session->setFlash("Couldn't find parameter: {$id}", 'flash/error');
					$this->redirect(array(
							'controller' => 'settings', 'action' => 'index', 'admin' => true )
					);
				}
				if ( $id === 'timezone' ) :
					$this->render('admin_timezone');
				endif;
			} else {
				$this->Setting->id = $id;
				if ( $this->Setting->save($this->request->data) ) {
					$this->Session->setFlash('Saved. @lo', 'flash/notice');
					$this->redirect(array( 'action' => 'index', $id ));
				} else {
					$this->Session->setFlash('Something went wrong @lo', 'flash/error');
				}
			}
		}

	}

?>