<?php
App::uses('AppModel', 'Model');
class AweberAppModel extends AppModel {
	var $useDbConfig = 'aweber'; // TODO: Softcode this

	protected function _generateCacheKey($fceName, $conditions = null) {
		$cacheKey = array();
		$cacheKey[] = $this->alias;
		$cacheKey[] = $fceName;
		if ($conditions) {
			$cacheKey[] = md5(serialize($conditions));	
		}
		return implode('_', $cacheKey);
	}
}
