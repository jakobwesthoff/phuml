<?php

class plProcessorOptions 
{
    const BOOL    = 1;
    const STRING  = 2;
    const DECIMAL = 3;

    protected $properties = array();    

    public function __get( $key )
    {
        if ( !array_key_exists( $key, $this->properties ) )
        {
            throw new plProcessorOptionException( $key, plProcessorOptionException::READ );
        }
        return $this->properties[$key]['data'];
    }

    public function __set( $key, $val )
    {
        if ( !array_key_exists( $key, $this->properties ) )
        {
            throw new plProcessorOptionException( $key, plProcessorOptionException::WRITE );
        }
        $this->properties[$key]['data'] = $val;            
    }

    public function getOptions() 
    {
        $options = array();
        foreach( $this->properties as $key => $property ) 
        {
            $options[] = $key;
        }
        return $options;
    }

    public function getOptionDescription( $option ) 
    {
        if ( !array_key_exists( $option, $this->properties ) ) 
        {
            throw new plProcessorOptionException( $option, plProcessorOptionException::UNKNOWN );
        }
        return $this->properties[$option]['description'];
    }

    public function getOptionType( $option ) 
    {
        if ( !array_key_exists( $option, $this->properties ) ) 
        {
            throw new plProcessorOptionException( $option, plProcessorOptionException::UNKNOWN );
        }
        return $this->properties[$option]['type'];
    }
}

?>
