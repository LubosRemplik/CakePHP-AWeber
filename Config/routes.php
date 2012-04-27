<?php
Router::connect(
	'/aweber/:controller/:action/*',
	array(
		'plugin' => 'aweber'
	)
);
