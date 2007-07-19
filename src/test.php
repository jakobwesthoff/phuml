<?php

require_once( 'config/config.php' );

$phuml = new plPhuml();

$phuml->addDirectory( dirname( __FILE__ ) );
$phuml->addProcessor( new plDotProcessor() );
$phuml->generate( 'test.dot' );

?>
