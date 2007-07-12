<?php

abstract class plStructureGenerator 
{
    public static function factory( $generator ) 
    {
        $classname = 'plStructure' . ucfirst( $generator ) . 'Generator';
        if ( class_exists( $classname ) === false ) 
        {
            throw new plStructureGeneratorNotFoundException( $generator );
        }
        return new $classname();
    }

    public abstract function createStructure( array $files );    
}

?>
