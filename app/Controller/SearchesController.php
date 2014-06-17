<?php

	App::uses('AppController', 'Controller');
	App::uses('SimpleSearchString', 'Lib');

	class SearchesController extends AppController {

		public $components = [
				'Paginator',
				'Search.Prg' => [
					'commonProcess' => [
						'allowedParams' => ['nstrict'],
						'keepPassed' => true,
						'filterEmpty' => true,
						'paramType' => 'querystring'
					]
				]
		];

		public $uses = [
				'Entry'
		];

		protected $_paginateConfig = [
				'limit' => 25
		];

		public function simple() {
			$defaults = [
					'order' => 'time'
			];
			$this->set('order', $defaults['order']);

			// @todo pgsql
			$db = $this->Entry->getDataSource();
			if (!($db instanceof Mysql)) {
				$this->redirect(['action' => 'advanced']);
				return;
			}

			$minWordLength = $this->Entry->query("SHOW VARIABLES LIKE 'ft_min_word_len'")[0]['VARIABLES']['Value'];
			$this->set(compact('minWordLength'));

			if (!isset($this->request->query['q'])) {
				// request for empty search form
				return;
			}

			$this->_filterQuery(['q', 'page', 'order']);
			$qRaw = $this->request->query['q'];
			$query = $this->request->query += $defaults;
			$this->set(['q' => $qRaw, 'order' => $query['order']]);

			// test query is valid
			$SearchString = new SimpleSearchString($qRaw, $minWordLength);

			if (!$SearchString->validateLength()) {
				$this->Entry->validationErrors = ['q' => ['minWordLength']];
				return;
			}
			$query['q'] = $SearchString->replaceOperators();

			// sanitize search-term for manual SQL-query
			$query['q'] = $this->_sanitize($query['q']);

			// build query
			$q = $query['q'];
			$order = '`Entry`.`time` DESC';
			$fields = '*';
			if ($query['order'] === 'rank') {
				$order = 'rating DESC, ' . $order;
				$fields = $fields . ", (MATCH (Entry.subject) AGAINST ('$q' IN BOOLEAN MODE)*2) + (MATCH (Entry.text) AGAINST ('$q' IN BOOLEAN MODE)) + (MATCH (Entry.name) AGAINST ('$q' IN BOOLEAN MODE)*4) AS rating";
			}

			// query
			$this->Paginator->settings = [
					'fields' => $fields,
					'conditions' => [
							"MATCH (Entry.subject, Entry.text, Entry.name) AGAINST ('$q' IN BOOLEAN MODE)",
							'Entry.category' => $this->Entry->Category->getCategoriesForAccession($this->CurrentUser->getMaxAccession())
					],
					'order' => $order,
					'paramType' => 'querystring'
			];
			$this->Paginator->settings += $this->_paginateConfig;
			$results = $this->Paginator->paginate('Entry');
			$this->set('results', $results);
		}

		/**
		 * @throws NotFoundException
		 * @throws BadRequestException
		 */
		public function advanced() {
			// year for date drop-down
			$first = $this->Entry->find('first',
					['contain' => false, 'order' => 'Entry.id ASC']);
			if ($first !== false) {
				$startDate = strtotime($first['Entry']['time']);
			} else {
				$startDate = time();
			}
			$this->set('start_year', date('Y', $startDate));

			// category drop-down
			$maxAccession = $this->CurrentUser->getMaxAccession();
			$categories = $this->Entry->Category->getCategoriesSelectForAccession(
					$maxAccession);
			$this->set('categories', $categories);

			// calculate current month and year
			if (isset($this->request->query['month'])) {
				$month = $this->request->query['month'];
				$year = $this->request->query['year'];
			} else {
				$month = date('n', $startDate);
				$year = date('Y', $startDate);
			}

			$this->Prg->commonProcess();
			$query = $this->Prg->parsedParams();

			if (!empty($query['subject']) || !empty($query['text']) ||
					!empty($query['name'])
			) {
				// strict username search: set before parseCriteria
				if (!empty($this->request->query['nstrict'])) {
					// presetVars controller var isn't working in Search v2.3
					$this->Entry->filterArgs['name']['type'] = 'value';
				}

				$settings = [
								'conditions' => $this->Entry->parseCriteria($query),
								'order' => ['Entry.time' => 'DESC'],
								'paramType' => 'querystring'
						] + $this->_paginateConfig;

				$time = mktime(0, 0, 0, $month, 1, $year);
				if (!$time) {
					throw new BadRequestException;
				}
				$settings['conditions']['time >'] = date(
						'Y-m-d H:i:s',
						mktime(0, 0, 0, $month, 1, $year));

				if (isset($query['category']) && (int)$query['category'] !== 0) {
					if (!isset($categories[(int)$query['category']])) {
						throw new NotFoundException;
					}
				} else {
					$settings['conditions']['Entry.category'] =
							$this->Entry->Category->getCategoriesForAccession($maxAccession);
				}
				$this->Paginator->settings = $settings;
				$this->set('results', $this->Paginator->paginate());
			}

			if (!isset($query['category'])) {
				$this->request->data['Entry']['category'] = 0;
			}

			$this->set(compact('month', 'year'));
		}

		protected function _sanitize($string) {
			return Sanitize::escape($string, $this->Entry->useDbConfig);
		}

		protected function _filterQuery($params) {
			$this->request->query = array_intersect_key($this->request->query,
					array_fill_keys($params, 1));
		}

	}
