<?php

class plProcessorNotFoundException extends Exception
{
    public function __construct( $processor ) 
    {
        parent::__construct( 'The needed processor class "' . $processor . '" could not be found.' );
    }
}

?>
