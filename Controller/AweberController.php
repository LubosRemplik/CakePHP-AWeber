<?php
App::uses('AppController', 'Controller');
class AweberController extends AppController {

	public $uses = array(
		'Aweber.AweberCollections',
	);

	public $components = array(
		'Apis.Oauth' => 'aweber',
		'Encrypt.Decrypt',
		'Frontpage.Frontpage'
	);

	public function beforeFilter() {
		parent::beforeFilter();
		if (!$this->Session->check('OAuth.aweber.oauth_token')
		&& FrontpageSite::get('aweber_oauth_token')) {
			$this->Session->write(
				'OAuth.aweber.oauth_token', 
				FrontpageSite::get('aweber_oauth_token')
			);
		}
		if (!$this->Session->check('OAuth.aweber.oauth_token_secret')
		&& FrontpageSite::get('aweber_oauth_token_secret')) {
			$this->Session->write(
				'OAuth.aweber.oauth_token_secret', 
				FrontpageSite::get('aweber_oauth_token_secret')
			);
		}
	}

	public function setSubscriber($email, $name = null) {
		$query = array();
		$query['name'] = urldecode($name);
		if (isset($email)) {
			$query['email'] = urldecode($email);
		}
		return $this->AweberCollections->setSubscriber($query);
	}

	public function connect($redirect = null) {
		$this->Oauth->connect(unserialize($this->Decrypt->hex2bin($redirect)));
	}

	public function aweber_callback() {
		Cache::clear();
		$this->Oauth->callback();
	}
}
