<?php

return array( 

    'plProcessor'                                   =>  'interfaces/processor.php',
    'plProcessorOptions'                            =>  'classes/processor/options.php',
    'plProcessorOptionException'                    =>  'exceptions/processor/option.php',

    'plGraphvizProcessor'                           =>  'classes/processor/graphviz.php',
    'plGraphvizProcessorOptions'                    =>  'classes/processor/graphviz/options.php',

    'plProcessorNotFoundException'                  =>  'exceptions/processor/notFound.php',

    'plGraphvizProcessorStyle'                      =>  'interfaces/processor/graphviz/style.php',
    'plGraphvizProcessorDefaultStyle'               =>  'classes/processor/graphviz/style/default.php',

    'plGraphvizProcessorStyleNotFoundException'     =>  'exceptions/processor/graphviz/style/notFound.php',

    'plExternalCommandProcessor'                    =>  'interfaces/processor/externalCommand.php',
    'plNeatoProcessor'                              =>  'classes/processor/neato.php',
    'plDotProcessor'                                =>  'classes/processor/dot.php',
    'plStatisticsProcessor'                         =>  'classes/processor/statistics.php',
    'plProcessorExternalExecutionException'         =>  'exceptions/processor/externalExecution.php',
);

?>
