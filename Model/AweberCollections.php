<?php
App::uses('AweberAppModel', 'Aweber.Model');
class AweberCollections extends AweberAppModel {

	/**
	 * A collection of accounts that you are authorized to access.
	 *
	 * https://labs.aweber.com/docs/reference/1.0#accounts
	 **/
	public function getAccounts() {
		$cacheKey = $this->_generateCacheKey('getAccounts');
		if (($data = Cache::read($cacheKey)) === false) {
			$data = $this->find('all', array(
				'path' => 'accounts',
			));
			return $data;
			Cache::write($cacheKey, $data);
		}
		return $data;
	}

	/**
	 * A collection of lists for a given account
	 *
	 * https://labs.aweber.com/docs/reference/1.0#lists
	 **/
	public function getLists() {
		$cacheKey = $this->_generateCacheKey('getLists');
		if (($data = Cache::read($cacheKey)) === false) {
			$path = array();
			$path[] = 'accounts';
			$path[] = Set::extract($this->getAccounts(), 'entries.0.id');
			$path[] = 'lists';
			$data = $this->find('all', array(
				'path' => implode('/', $path)
			));
			return $data;
			Cache::write($cacheKey, $data);
		}
		return $data;
	}

	/**
	 * A collection of subscribers for a given list.
	 *
	 * https://labs.aweber.com/docs/reference/1.0#subscribers
	 **/
	public function getSubscribers($query = array()) {
		$cacheKey = $this->_generateCacheKey('getSubscribers');
		if (($data = Cache::read($cacheKey)) === false) {
			$path = array();
			$path[] = 'accounts';
			$path[] = Set::extract($this->getAccounts(), 'entries.0.id');
			$path[] = 'lists';
			$path[] = Set::extract($this->getLists(), 'entries.0.id');
			$path[] = 'subscribers';
			$data = $this->find('all', array(
				'path' => implode('/', $path),
				'query' => $query
			));
			return $data;
			Cache::write($cacheKey, $data);
		}
		return $data;
	}

	/**
	 * A collection of subscribers for a given list.
	 *
	 * https://labs.aweber.com/docs/reference/1.0#subscribers
	 **/
	public function setSubscriber($query = array()) {
		if (empty($query['email'])) {
			return false;
		}
		$oAuth = $_SESSION['OAuth']['aweber'];
		$consumerKey = $oAuth['oauth_consumer_key'];
		$consumerSecret = $oAuth['oauth_consumer_secret'];
		$accessKey = $oAuth['oauth_token'];
		$accessSecret = $oAuth['oauth_token_secret'];
		App::import('Vendor', 'Aweber.aweber/aweber_api/aweber');
		$aweber = new AWeberAPI($consumerKey, $consumerSecret);
		$account = $aweber->getAccount($accessKey, $accessSecret);
		$account_id = Set::extract($this->getAccounts(), 'entries.0.id');
		$list_id = Set::extract($this->getLists(), 'entries.0.id');
		$list = $account->loadFromUrl("/accounts/{$account_id}/lists/{$list_id}");
		$subscribers = $list->subscribers;
		$new_subscriber = $subscribers->create($query);
		return $new_subscriber;
	}
}
