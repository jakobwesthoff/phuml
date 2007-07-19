<?php

abstract class plProcessor 
{
    public static function factory( $processor ) 
    {
        $classname = 'pl' . ucfirst( $processor ) . 'Processor';
        if ( class_exists( $classname ) === false ) 
        {
            throw new plProcessorNotFoundException( $processor );
        }
        return new $classname();
    }

    public static function getProcessors() 
    {
        $processors = array();
        foreach( plBase::getAutoloadClasses() as $autoload ) 
        {
            if ( preg_match( '@^pl([A-Z][a-z]*)Processor$@', $autoload, $matches ) ) 
            {
                $processors[] = $matches[1];
            }
        }
        return $processors;
    }

    public function writeToDisk( $input, $output ) 
    {
        file_put_contents( $output, $input );
    }
    
    abstract public function getInputTypes();
    abstract public function getOutputType();
    abstract public function process( $input, $type );

}

?>
