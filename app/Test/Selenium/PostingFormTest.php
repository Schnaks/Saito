<?php

	require_once 'Lib/SaitoSeleniumTestCase.php';

	class PostingFormTest extends SaitoSeleniumTestCase {

		public function testEntryAddFocusHasFocus() {
			$this->login();

			$this->click("css=.btn-entryAdd");
			$this->waitForPageToLoad("30000");

			$focused = $this->getEval('window.jQuery("#EntrySubject").is(":focus");');
			$this->assertEquals('true', $focused);
		}

		public function tes1PostingFormCase() {
			$this->login();
			$thread = new SaitoTestThread($this);
			$thread->openAddNewThreadForm();

			$this->assertTrue($this->isElementPresent("EntrySubject"));

			// test gacker button
			$this->type("EntryText", "");
			$this->clickAt("link=Gacker", "1,1");
			$this->assertEquals(":gacker:", $this->getValue("EntryText"));

			// test popcorn button
			$this->type("EntryText", "");
			$this->clickAt("link=Popcorn", "1,1");
			$this->assertEquals(":popcorn:", $this->getValue("EntryText"));

			/*
			// test if newline and selenium work in the browser at all
			$this->type("EntryText", "");
			// $this->type("EntryText", "\n\ntest"); does work in IDE, but not RC, because RC trims
			// keyPress and typeKeys now working in chrome
			$this->focus('EntryText');
			$this->keyPressNative("10");
			$this->keyPressNative("10");
			$this->keyPressNative("49");
			sleep(1);
			$this->assertEquals("\r\r1", $this->getEval("window.document.getElementById('EntryText').value"));

			// test for newline error when using buttons
			$this->type("EntryText", "");
			$this->keyPressNative("10");
			$this->keyPressNative("10");
			sleep(1);
			$this->clickAt("link=Popcorn", "1,1");
			$this->assertEquals("\n\n:popcorn:", $this->getEval("window.document.getElementById('EntryText').value"));
			 *
			 */

			/* not stable
			// check standard value for form elements
			$this->setCursorPosition("EntrySubject", "-1");
			$this->assertEquals(0, $this->getCursorPosition("EntrySubject"));
			$this->setCursorPosition("EntryText", "-1");
			$this->assertEquals(0, $this->getCursorPosition("EntryText"));
			*/

			$this->logout();
		}

	}
