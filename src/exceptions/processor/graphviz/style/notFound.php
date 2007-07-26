<?php

class plGraphvizProcessorStyleNotFoundException extends Exception
{
    public function __construct( $style ) 
    {
        parent::__construct( 'The needed dot style "' . $style . '" could not be found.' );
    }
}

?>
