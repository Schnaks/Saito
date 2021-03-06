<?php

  /**
   * Saito Enduser Configuration
   */

  /**
   * Setting default language (mandatory)
   *
   * Use ISO 639-2 Code http://www.loc.gov/standards/iso639-2/php/code_list.php
   * So german would be: deu
   */
  Configure::write('Config.language', 'eng');

	/**
	 * Sets the markup parser
	 *
	 * Parser hould be placed in app/Plugin/<name>Parser
	 */
	Configure::write('Saito.Settings.ParserPlugin', 'Bbcode');

  /**
   * Sets the default theme
   */
  Configure::write('Saito.themes.default', 'Paz');

	/**
	 * Sets additional themes available for all users
	 *
	 * `*` - all installed themes (in Themed folder)
	 * `['A', 'B']` - only themes 'A' and 'B' (Themed folder names)
	 */
	// Configure::write('Saito.themes.available.all', '*');

	/**
	 * Sets additional themes available for specific users only
	 *
	 * [<user-id> => '<theme name>', …]
	 */
	 // Configure::write('Saito.themes.available.users', [1 => ['C']]);

	/**
	 * Sets the X-Frame-Options header send with each request
	 */
	Configure::write('Saito.X-Frame-Options', 'SAMEORIGIN');

  /**
   * Add additional buttons to editor
	 *
	 * You can theme them with
	 *
	 * <code>
	 * 	.markItUp .markItUpButton<Id> a {
	 * 		…
	 *	}
	 * </code>
   *
  /*
  Configure::write(
      'Saito.markItUp.additionalButtons',
      array(
        'Button1' => array(
  					// button-text
  					'name' => 'Do Something',
  					// hover title
            'title'       => 'Button 1',
            // code inserted into text
            'code' 				=> ':action:',

						// image in img/markitup/<icon-name>, replaces `name` (optional)
						'icon'			=> 'icon-name.png', (optional)
            // format replacement as image (optional)
            'type'				=> 'image',
            // replacement in output if type is image
            // image in img/markitup/<replacement>
            'replacement' => 'resultofbutton1.png'
          ),
  * 			// …
        )
      );
  *
  */

  /**
   * Users to notify via email if a new users registers successfully
	 *
	 * Provide an array with user IDs. To notify the admin (usually user-id 1):
	 *
	 *     [1]
	 *
	 * To notify the admin with id 1 and the user with the id 5:
	 *
	 *     [1, 5]
   */
  /*
  Configure::write('Saito.Notification.userActivatedAdminNoticeToUserWithID', [1]);
   */
