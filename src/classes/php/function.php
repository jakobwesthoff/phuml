<?php

class plPhpFunction
{
    private $properties;

    public function __construct( $name, $modifier = 'public', $params = array() ) 
    {
        $this->properties = array( 
            'name'      =>  $name,
            'modifier'  =>  $modifier,
            'params'    =>  $params,
        );
    }

    public function __get( $key )
    {
        if ( !array_key_exists( $key, $this->properties ) )
        {
            throw new plBasePropertyException( $key, plBasePropertyException::READ );
        }
        return $this->properties[$key];
    }

    public function __set( $key, $val )
    {
        if ( !array_key_exists( $key, $this->properties ) )
        {
            throw new plBasePropertyException( $key, plBasePropertyException::WRITE );
        }
        $this->properties[$key] = $val;            
    }
}

?>
