<?php

abstract class plProcessorOptions 
{
    protected $properties = array();

    public function __get( $key )
    {
        if ( !array_key_exists( $key, $this->properties ) )
        {
            throw new plProcessorOptionException( $key, plProcessorOptionException::READ );
        }
        return $this->properties[$key];
    }

    public function __set( $key, $val )
    {
        if ( !array_key_exists( $key, $this->properties ) )
        {
            throw new plProcessorOptionException( $key, plProcessorOptionException::WRITE );
        }
        $this->properties[$key] = $val;            
    }
}

?>
