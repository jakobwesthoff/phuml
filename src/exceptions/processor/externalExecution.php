<?php

class plProcessorExternalExecutionException extends Exception 
{
    public function __construct( $output ) 
    {
    	// Convert array to string if is_array
    	if(is_array($output)) {
    		$output = var_export($output, true);
    	}
        parent::__construct( 'Execution of external program failed:' . "\n" . $output );
    }
}

?>
