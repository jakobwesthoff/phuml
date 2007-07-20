<?php

return array( 

    'plProcessor'                                   =>  'interfaces/processor.php',
    'plProcessorOptions'                            =>  'interfaces/processor/options.php',
    'plProcessorOptionException'                    =>  'exceptions/processor/option.php',

    'plDotProcessor'                                =>  'classes/processor/dot.php',
    'plDotProcessorOptions'                         =>  'classes/processor/dot/options.php',

    'plProcessorNotFoundException'                  =>  'exceptions/processor/notFound.php',

    'plDotProcessorStyle'                           =>  'interfaces/processor/dot/style.php',
    'plDotProcessorDefaultStyle'                    =>  'classes/processor/dot/style/default.php',

    'plDotProcessorStyleNotFoundException'          =>  'exceptions/processor/dot/style/notFound.php',

    'plExternalCommandProcessor'                    =>  'interfaces/processor/externalCommand.php',
    'plNeatoProcessor'                              =>  'classes/processor/neato.php',
    'plProcessorExternalExecutionException'         =>  'exceptions/processor/externalExecution.php',
);

?>
