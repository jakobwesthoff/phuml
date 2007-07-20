<?php

class plProcessorExternalExecutionException extends Exception 
{
    public function __construct( $output ) 
    {
        parent::__construct( 'Execution of external program failed:' . "\n" . $output );
    }
}

?>
