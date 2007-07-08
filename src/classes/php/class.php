<?php

class plPhpClass
{
    private $properties;

    public function __construct( $name, $variables = array(), $functions = array(), $implements = array(), $extends = null ) 
    {
        $this->properties = array( 
            'name'          =>  $name,
            'variables'     =>  $variables,
            'functions'     =>  $functions,
            'implements'    =>  $implements,
            'extends'       =>  $extends,
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
