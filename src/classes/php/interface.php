<?php

class plPhpInterface
{
    private $properties;

    public function __construct( $name, $functions = array(), $extends = null ) 
    {
        $this->properties = array( 
            'name'      =>  $name,
            'functions' =>  $functions,
            'extends'   =>  $extends,
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
