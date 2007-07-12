<?php

class plStructureWriterNotFoundException extends Exception
{
    public function __construct( $writer ) 
    {
        parent::__construct( 'The needed writer class "' . $writer . '" could not be found.' );
    }
}

?>
