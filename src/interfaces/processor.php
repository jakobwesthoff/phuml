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

    public function writeToDisk( $input, $output ) 
    {
        file_put_contents( $output, $input );
    }
    
    abstract function getInputTypes();
    abstract function getOutputType();
    abstract function process( $input, $type );

}

?>
