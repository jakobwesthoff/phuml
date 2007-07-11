<?php

class plPhuml 
{
    private $properties;
    
    private $files;

    public function __construct() 
    {
        $this->properties = array( 
            'generator'     => new plStructureGeneratorTokenizer(),
            'writer'        => new plStructureWriterDot(),
        );

        $this->files = array();
    }

    public function addFile( $file ) 
    {

    }

    public function addDirectory( $directory ) 
    {

    }

    public function generate( $outfile ) 
    {

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
