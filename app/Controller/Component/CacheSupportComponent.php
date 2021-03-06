<?php

	use Saito\Cache\CacheSupport;
	use Saito\Cache\ItemCache;
	use Saito\Cache\LineCacheSupportCachelet;
	use Saito\Cache\SaitoCacheEngineAppCache;

	App::uses('Component', 'Controller');

	class CacheSupportComponent extends Component {

		protected $_CacheSupport;

		/** * @var ItemCache */
		public $LineCache;

		public function initialize(Controller $Controller) {
			$this->_CacheSupport = new CacheSupport();
			if ($Controller->modelClass) {
				$Controller->{$Controller->modelClass}->SharedObjects['CacheSupport'] = $this->_CacheSupport;
			}
			$this->_addConfigureCachelets();
			$this->_initLineCache($Controller);
		}

		protected function _initLineCache() {
			$this->LineCache = new ItemCache(
				'Saito.LineCache',
				new SaitoCacheEngineAppCache,
				// duration: update relative time values in HTML at least every hour
				['duration' => 3600, 'maxItems' => 600]
			);
			$this->_CacheSupport->add(new LineCacheSupportCachelet($this->LineCache));
		}

		/**
		 * Adds additional cachelets from Configure `Saito.Cachelets`
		 *
		 * E.g. use in `Plugin/<foo>/Config/bootstrap.php`:
		 *
		 * <code>
		 * Configure::write('Saito.Cachelets.M', ['location' => 'M.Lib', 'name' => 'MCacheSupportCachelet']);
		 * </code>
		 */
		protected function _addConfigureCachelets() {
			$_additionalCachelets = Configure::read('Saito.Cachelets');
			if (!$_additionalCachelets) {
				return;
			}
			foreach ($_additionalCachelets as $_c) {
				App::uses($_c['name'], $_c['location']);
				$this->_CacheSupport->add(new $_c['name']);
			}
		}

		public function beforeRender(Controller $Controller) {
			$Controller->set('LineCache', $this->LineCache);
		}

		public function __call($method, $params) {
			$proxy = [$this->_CacheSupport, $method];
			if (is_callable($proxy)) {
				return call_user_func_array($proxy, $params);
			}
		}

	}
