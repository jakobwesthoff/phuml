<?php

define( "BASEDIR", dirname( __FILE__ ) . '/..' );

ini_set( 
    ini_get( "include_path" ) . PATH_SEPARATOR .
    "include_path", 
    BASEDIR
);

require_once( 'classes/base.php' );

plBase::addAutoloadDirectory( dirname( __FILE__ ). '/../autoload' );

function __autoload( $classname )
{
    plBase::autoload( $classname );
}

?>
