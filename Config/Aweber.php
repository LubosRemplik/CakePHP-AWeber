<?php
/**
 * A Aweber API Method Map
 *
 * Refer to the apis plugin for how to build a method map
 * https://github.com/ProLoser/CakePHP-Api-Datasources
 *
 */
$config['Apis']['Aweber']['hosts'] = array(
	'oauth' => 'auth.aweber.com/1.0/oauth',
	'rest' => 'api.aweber.com/1.0',
);
// http://aweber.com/api/docs/advanced-api
$config['Apis']['Aweber']['oauth'] = array(
	'authorize' => 'authorize', // Example URI: api.aweber.com/uas/oauth/authorize
	'request' => 'request_token',
	'access' => 'access_token',
	'login' => 'authenticate', // Like authorize, just auto-redirects
	'logout' => 'invalidate_token',
);
