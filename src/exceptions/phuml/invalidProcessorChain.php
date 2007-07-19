<?php

class plPhumlInvalidProcessorChainException extends Exception 
{
    public function __construct( $first, $second ) 
    {
        parent::__construct( 
            'To processors in the chain are incompatible. The first processor\'s output is "' 
          . $first 
          . '". The next Processor in the queue does only support the following input types: ' 
          . implode( ', ', $second ) 
          . '.' 
       );
    }
}

?>
