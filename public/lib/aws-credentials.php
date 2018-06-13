<?php

	use Aws\S3\S3Client;

	$client = S3Client::factory(array(
	'version' => 'latest',
    'region'  => 'eu-central-1',
    'credentials' => array(
       'key'    => 'AKIAIBNBBHSCGZHES4WA',
        'secret' => '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'
    )
));
?>