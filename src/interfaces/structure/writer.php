<?php

abstract class plStructureWriter 
{
    public static function factory( $writer ) 
    {
        $classname = 'plStructureWriter' . ucfirst( $writer );
        if ( class_exists( $classname ) === false ) 
        {
            throw new plStructureWriterNotFoundException( $writer );
        }
        return new $classname();
    }

    public abstract function writeStructure( $structure );   
}

?>
