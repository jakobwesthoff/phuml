<?php

class plStructureGeneratorNotFoundException extends Exception
{
    public function __construct( $generator ) 
    {
        parent::__construct( 'The needed generator class "' . $generator . '" could not be found.' );
    }
}

?>
