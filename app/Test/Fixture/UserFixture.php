<?php

	class UserFixture extends CakeTestFixture {

		public $fields = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
			'user_type' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'username' => array('type' => 'string', 'null' => true, 'default' => null, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'user_real_name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'password' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'user_email' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'hide_email' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
			'user_hp' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'user_place' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'signature' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'profile' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
			'entry_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'logins' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'last_login' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
			'last_logout' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
			'registered' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
			'last_refresh' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
			'last_refresh_tmp' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
			'user_view' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'new_posting_notify' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
			'new_user_notify' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
			'personal_messages' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
			'time_difference' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
			'user_lock' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
			'pwf_code' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'activate_code' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 7),
			'user_signatures_hide' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
			'user_signatures_images_hide' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
			'user_forum_refresh_time' => array('type' => 'integer', 'null' => true, 'default' => '0'),
			'user_forum_hr_ruler' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
			'user_automaticaly_mark_as_read' => array('type' => 'integer', 'null' => true, 'default' => '1', 'length' => 4),
			'user_sort_last_answer' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
			'user_color_new_postings' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'user_color_actual_posting' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'user_color_old_postings' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'user_show_own_signature' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
			'slidetab_order' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 512, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'show_userlist' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'comment' => 'stores if userlist is shown in front layout'),
			'show_recentposts' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
			'show_recententries' => array('type' => 'boolean', 'null' => false, 'default' => null),
			'show_shoutbox' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
			'inline_view_on_click' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
			'user_show_thread_collapsed' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
			'flattr_uid' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 24, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'flattr_allow_user' => array('type' => 'boolean', 'null' => true, 'default' => null),
			'flattr_allow_posting' => array('type' => 'boolean', 'null' => true, 'default' => null),
			'user_category_override' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
			'user_category_active' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'user_category_custom' => array('type' => 'string', 'null' => false, 'length' => 512, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'indexes' => array(
				'PRIMARY' => array('column' => 'id', 'unique' => 1),
				'username' => array('column' => 'username', 'unique' => 1)
			),
			'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
		);

		public $records = array(
			array(
				'id' => 1,
				'username' => 'Alice',
				'user_type' => 'admin',
				'user_email' => 'alice@example.com',
				// `test`
				'password' => '098f6bcd4621d373cade4e832627b4f6',
				'slidetab_order' => null,
				'user_automaticaly_mark_as_read' => 0,
				'user_lock' => 0,
				'personal_messages' => 0,
				'user_category_custom' => '', // used in test-case, don't change
				'registered' => '2009-01-01 00:00',
				'activate_code' => 0,
			),
			array(
				'id' => 2,
				'username' => 'Mitch',
				'user_type' => 'mod',
				'user_email' => 'mitch@example.com',
				'password' => '098f6bcd4621d373cade4e832627b4f6',
				'slidetab_order' => null,
				'user_automaticaly_mark_as_read' => 0,
				'user_lock' => 0,
				'personal_messages' => 0,
				'user_category_custom' => '',
				'registered' => '2009-01-01 00:00',
				'activate_code' => 0,
			),
			array(
				'id' => 3,
				'username' => 'Ulysses',
				'user_type' => 'user',
				'user_email' => 'ulysses@example.com',
				'password' => '098f6bcd4621d373cade4e832627b4f6',
				'slidetab_order' => null,
				'user_automaticaly_mark_as_read' => 0,
				'user_lock' => 0,
				'personal_messages' => 1,
				'user_category_custom' => '',
				'registered' => '2009-01-01 00:00',
				'activate_code' => 0,
			),
			array(
				'id' => 4,
				'username' => 'Change Password Test',
				'user_type' => 'user',
				'user_email' => 'cpw@example.com',
				'password' => '098f6bcd4621d373cade4e832627b4f6',
				'slidetab_order' => null,
				'user_automaticaly_mark_as_read' => 1,
				'user_lock' => 0,
				'personal_messages' => 0,
				'user_category_custom' => '',
				'registered' => '2009-01-01 00:00',
				'activate_code' => 0,
			),
			array(
				'id' => 5,
				'username' => 'Uma',
				'user_type' => 'user',
				'user_email' => 'uma@example.com',
				'password' => '098f6bcd4621d373cade4e832627b4f6',
				'slidetab_order' => null,
				'user_automaticaly_mark_as_read' => 1,
				'user_lock' => 0,
				'personal_messages' => 0,
				'user_category_custom' => '',
				'registered' => '2009-01-01 00:00',
				'activate_code' => 0,
			),
			array(
				'id' => 6,
				'username' => 'Second Admin',
				'user_type' => 'admin',
				'user_email' => 'second admin@example.com',
				'password' => '$2a$10$7d0000dd8a37f797acb53OY.oaPgJ2vV4PE3.VBpmsm9OM/zMlzNq',
				//testtest
				'slidetab_order' => null,
				'user_automaticaly_mark_as_read' => 1,
				'user_lock' => 0,
				'personal_messages' => 0,
				'user_category_custom' => '',
				'registered' => '2009-01-01 00:00',
				'activate_code' => 0,
			),
		);

	}
