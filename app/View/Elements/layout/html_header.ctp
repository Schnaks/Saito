<?= $this->Html->docType('html5'); ?>
<html>
	<head>
    <title><?= $title_for_layout ?></title>
		<?= $this->Html->charset(); ?>
		<link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico" />
		<?php
			echo $this->fetch('meta');
			echo $this->fetch('css');

			echo $this->Html->css('stylesheets/static.css');
			echo $this->Html->css('stylesheets/styles.css');

			if (Configure::read('debug') > 0) {
				echo $this->Html->css('stylesheets/cake.css');
			}

			if (isset($CurrentUser) && $CurrentUser->isLoggedIn()) :
				echo $this->UserH->generateCss($CurrentUser->getSettings());
			endif;

			$this->Session->flash();
			$this->Session->flash('email');
			// @td after full js refactoring and moving getAppJs to the page bottom
			// this should go into View/Users/login.ctp again
			$this->Session->flash('auth', array('element' => 'flash/warning'));
			echo $this->Html->scriptBlock($this->JsData->getAppJs($this));

			echo $this->jQuery->scriptTag();
			if (Configure::read('debug') == 0):
				echo $this->RequireJs->scriptTag('main-prod');
			else:
				echo $this->RequireJs->scriptTag('main');
			endif;
		?>
		<?php
			/*
			 * fixing safari mobile fubar;
			 * see: http://stackoverflow.com/questions/6448465/jquery-mobile-device-scaling
			 */
			?>
		<meta name="viewport" content="height=device-height,width=device-width" />
		<script type="text/javascript">
			//<![CDATA[
    if (navigator.userAgent.match(/iPad/i)) {
        var $viewport = $('head').children('meta[name="viewport"]');
        $(window).bind('orientationchange', function() {
            if (window.orientation == 90 || window.orientation == -90 || window.orientation == 270) {
                $viewport.attr('content', 'height=device-width,width=device-height,initial-scale=1.0');
            } else {
                $viewport.attr('content', 'height=device-height,width=device-width,initial-scale=1.0');
            }
        }).trigger('orientationchange');
    }
			//]]>
		</script>
		<?= $this->fetch('htmlHead'); ?>