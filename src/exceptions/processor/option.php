<?php

class plProcessorOptionException extends Exception 
{
    const READ = 1;
    const WRITE = 2;
    const UNKNOWN = 3;
    
    public function __construct( $key, $type ) 
    {
        parent::__construct( 
            'The option "' 
            . $key 
            . '" is not '
            . ( $type === self::READ ) 
              ? ( 'readable' )
              : ( ( $type === self::UNKNOWN )
                ? ( 'existent' )
                : ( 'writable')
              ) 
            . ' in this context.'
        );
    }
}

?>
