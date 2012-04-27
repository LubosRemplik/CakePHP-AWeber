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
	public function getLists($options = array()) {
		$cacheKey = $this->_generateCacheKey('getLists', $options);
		if (($data = Cache::read($cacheKey)) === false) {
			$path = array();
			$path[] = 'accounts';
			if (isset($options['account_id'])) {
				$path[] = $options['account_id'];
			} else {
				$path[] = Set::extract($this->getAccounts(), 'entries.0.id');
			}
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
	public function getSubscribers($query = array(), $options = array()) {
		$cacheKey = $this->_generateCacheKey('getSubscribers', am($query, $options));
		if (($data = Cache::read($cacheKey)) === false) {
			$path = array();
			$path[] = 'accounts';
			if (isset($options['account_id'])) {
				$path[] = $options['account_id'];
			} else {
				$path[] = Set::extract($this->getAccounts(), 'entries.0.id');
			}
			$path[] = 'lists';
			if (isset($options['list_id'])) {
				$path[] = $options['list_id'];
			} else {
				$path[] = Set::extract($this->getLists(), 'entries.0.id');
			}
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
	public function setSubscriber($query = array(), $options = array()) {
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
		if (isset($options['account_id'])) {
			$account_id = $options['account_id'];
		} else {
			$account_id = Set::extract($this->getAccounts(), 'entries.0.id');
		}
		if (isset($options['list_id'])) {
			$list_id = $options['list_id'];
		} else {
			$list_id = Set::extract($this->getLists(), 'entries.0.id');
		}
		$list = $account->loadFromUrl("/accounts/{$account_id}/lists/{$list_id}");
		$subscribers = $list->subscribers;
		$new_subscriber = $subscribers->create($query);
		return $new_subscriber;
	}
}
