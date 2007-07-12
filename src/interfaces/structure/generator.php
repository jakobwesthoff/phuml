<?php

abstract class plStructureGenerator 
{
    public static function factory( $generator ) 
    {
        $classname = 'plStructureGenerator' . ucfirst( $generator );
        if ( class_exists( $classname ) === false ) 
        {
            throw new plStructureGeneratorNotFoundException( $generator );
        }
        return new $classname();
    }

    public abstract function createStructure( array $files );    
}

?>
