<?php

return array(
    'pdf' => array(
        'enabled' => true,
        'binary' => '/usr/local/bin/wkhtmltopdf',
		 #'binary' => '/usr/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary' => '/usr/local/bin/wkhtmltoimage',
		#'binary' => '/usr/bin/wkhtmltoimage',
        'timeout' => false,
        'options' => array(),
    ),
);