<?php

abstract class plStructureWriter 
{
    public static function factory( $writer ) 
    {
        $classname = 'plStructure' . ucfirst( $writer ) . 'Writer';
        if ( class_exists( $classname ) === false ) 
        {
            throw new plStructureWriterNotFoundException( $writer );
        }
        return new $classname();
    }

    public abstract function writeStructure( $structure );   
}

?>
