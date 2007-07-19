<?php

class plPhumlInvalidProcessorException extends Exception 
{
    public function __construct() 
    {
        parent::__construct( 'The supplied processor is invalid.' );
    }
}

?>
