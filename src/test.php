<?php

require_once( 'config/config.php' );

$phuml = new plPhuml();

$phuml->addDirectory( dirname( __FILE__ ) );
$phuml->addProcessor( new plDotProcessor() );
$phuml->addProcessor( new plNeatoProcessor() );
$phuml->generate( 'testX.png' );

?>
