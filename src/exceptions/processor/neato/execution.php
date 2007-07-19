<?php

class plNeatoProcessorExecutionException extends Exception 
{
    public function __construct( $output ) 
    {
        parent::__construct( 'Neato execution failed:' . "\n" . $output );
    }
}

?>
