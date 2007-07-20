<?php

return array( 

    'plProcessor'                                   =>  'interfaces/processor.php',
    'plDotProcessor'                                =>  'classes/processor/dot.php',

    'plProcessorNotFoundException'                  =>  'exceptions/processor/notFound.php',

    'plDotProcessorStyle'                           =>  'interfaces/processor/dot/style.php',
    'plDotProcessorDefaultStyle'                    =>  'classes/processor/dot/style/default.php',

    'plDotProcessorStyleNotFoundException'          =>  'exceptions/processor/dot/style/notFound.php',

    'plExternalCommandProcessor'                    =>  'interfaces/processor/externalCommand.php',
    'plNeatoProcessor'                              =>  'classes/processor/neato.php',
    'plProcessorExternalExecutionException'         =>  'exceptions/processor/externalExecution.php',
);

?>
