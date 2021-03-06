<?php

	namespace Saito\User\Userlist;

	class UserlistModel implements UserlistInterface {

		protected $_userlist = [];

		protected $_User;

		public function set(\User $User) {
			$this->_User = $User;
		}

		public function get() {
			if (empty($this->_userlist)) {
				$this->_userlist = $this->_User->userlist();
			}
			return $this->_userlist;
		}

	}
