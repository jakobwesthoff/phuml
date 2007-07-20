<?php

class plDotProcessorOptions extends plProcessorOptions 
{    
    public function __construct() 
    {
        $this->properties = array( 
            'style'                 =>  plDotProcessorStyle::factory( 'default' ),
            'createAssociations'    =>  true,
        );
    }

    public function __set( $key, $val ) 
    {
        if ( !array_key_exists( $key, $this->properties ) )
        {
            throw new plProcessorOptionException( $key, plProcessorOptionException::WRITE );
        }

        switch( $key ) 
        {
            case 'style':
                $this->properties[$key] = plDotProcessorStyle::factory( $val );
            break;
            default:
                $this->properties[$key] = $val;
        }

    }
}

?>
