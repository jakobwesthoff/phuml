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
        $this->files[] = $file;
    }

    public function addDirectory( $directory, $extension = 'php', $recursive = true ) 
    {
        if ( $recursive === false ) 
        {
            $iterator = new DirectoryIterator( $directory );
        }
        else
        {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator( $directory )
            );
        }

        foreach( $iterator as $entry ) 
        {
            if ( $entry->isDir() === true ) 
            {
                continue;
            }
            
            if ( $sub = strtolower( substr( $entry->getFilename(), -1 * strlen( $extension ) ) ) !== strtolower( $extension ) ) 
            {
                continue;
            }

            $this->files[] = $entry->getPathname();
        }       
    }

    public function generate( $outfile ) 
    {
        $structure = $this->generator->createStructure( $this->files );
        $output    = $this->writer->writeStructure( $structure );

        file_put_contents( $outfile, $output );
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
