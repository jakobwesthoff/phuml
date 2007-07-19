<?php

abstract class plExternalCommandProcessor extends plProcessor 
{

    abstract public function execute( $infile, $outfile, $type );

    public function process( $input, $type ) 
    {
        // Create temporary datafiles
        $infile  = tempnam( '/tmp', 'phuml' );
        $outfile = tempnam( '/tmp', 'phuml' );
        
        file_put_contents( $infile, $input );

        unlink( $outfile );

        $this->execute( $infile, $outfile, $type );
        
        $outdata = file_get_contents( $outfile );

        unlink( $infile );
        unlink( $outfile );

        return $outdata;
    }
}

?>
