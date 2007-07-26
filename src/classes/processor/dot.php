<?php

class plDotProcessor extends plExternalCommandProcessor 
{
    public $options;

    public function __construct() 
    {
        $this->options = new plProcessorOptions();
    }

    public function getInputTypes() 
    {
        return array( 
            'text/dot',
        );
    }

    public function getOutputType() 
    {
        return 'image/png';
    }

    public function execute( $infile, $outfile, $type ) 
    {
        exec(
            'dot -Tpng -o ' . escapeshellarg( $outfile ) . ' ' . escapeshellarg( $infile ),
            $output,
            $return
        );

        if ( $return !== 0 ) 
        {
            throw new plProcessorExternalExecutionException( $output );
        }
    }
}

?>
