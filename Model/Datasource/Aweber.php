<?php
/**
 * Aweber DataSource
 **/
App::uses('ApisSource', 'Apis.Model/Datasource');
class Aweber extends ApisSource {
	
	/**
	 * The description of this data source
	 * @var string
	 */
	public $description = 'Aweber DataSource Driver';
	
	/**
	 * Set the datasource to use OAuth
	 *
	 * @param array $config
	 * @param HttpSocket $Http
	 */
	public function __construct($config) {
		$config['method'] = 'OAuth';
		parent::__construct($config);
	}

	public function read($model, $queryData = array()) {
		if (empty($queryData['path'])) {
			return false;
		}
		if (!isset($model->request)) {
			$model->request = array();
		}
		$model->request['method'] = 'GET';
		$model->request['uri']['path'] = $queryData['path'];
		if (!empty($queryData['query'])) {
			$model->request['uri']['query'] = $queryData['query'];
		}
		return $this->request($model);
	}

	public function beforeRequest($model, $request) {
		// setting scheme
		$request['uri']['scheme'] = 'https';
		return $request;
	}
}
