<?php

class plStatisticsProcessor extends plProcessor
{
    private $information;
    public $options;

    public function __construct() 
    {
        $this->options   = new plProcessorOptions();
        $this->information = array();
    }

    public function getInputTypes() 
    {
        return array( 
            'application/phuml-structure'
        );
    }

    public function getOutputType() 
    {
        return 'text/plain';
    }

    public function process( $input, $type ) 
    {
        // Initialize the values        
        $this->information['interfaceCount']           = 0;
        $this->information['classCount']               = 0;
        $this->information['publicFunctionCount']      = 0;
        $this->information['publicAttributeCount']     = 0;
        $this->information['publicTypedAttributes']    = 0;
        $this->information['protectedFunctionCount']   = 0;
        $this->information['protectedAttributeCount']  = 0;
        $this->information['protectedTypedAttributes'] = 0;
        $this->information['privateFunctionCount']     = 0;
        $this->information['privateAttributeCount']    = 0;
        $this->information['privateTypedAttributes']   = 0;

        // Loop through the classes and interfaces
        foreach ( $input as $definition ) 
        {
            if ( $definition instanceof plPhpInterface ) 
            {
                $this->information['interfaceCount']++;
            }

            if ( $definition instanceof plPhpClass ) 
            {
                $this->information['classCount']++;

                foreach( $definition->attributes as $attribute ) 
                {
                    switch ( $attribute->modifier ) 
                    {
                        case 'public':
                            $this->information['publicAttributeCount']++;
                            if ( $attribute->type !== null ) 
                            {
                                $this->information['publicTypedAttributes']++;
                            }
                        break;
                        case 'protected':
                            $this->information['protectedAttributeCount']++;
                            if ( $attribute->type !== null ) 
                            {
                                $this->information['protectedTypedAttributes']++;
                            }
                        break;
                        case 'private':
                            $this->information['privateAttributeCount']++;
                            if ( $attribute->type !== null ) 
                            {
                                $this->information['privateTypedAttributes']++;
                            }
                        break;                    
                    }
                }
            }

            foreach( $definition->functions as $function ) 
            {
                switch ( $function->modifier ) 
                {
                    case 'public':
                        $this->information['publicFunctionCount']++;
                    break;
                    case 'protected':
                        $this->information['protectedFunctionCount']++;
                    break;
                    case 'private':
                        $this->information['privateFunctionCount']++;
                    break;                    
                }
            }
        }

        $this->information['functionCount']       = $this->information['publicFunctionCount'] + $this->information['protectedFunctionCount'] + $this->information['privateFunctionCount'];
        $this->information['attributeCount']      = $this->information['publicAttributeCount'] + $this->information['protectedAttributeCount'] + $this->information['privateAttributeCount'];
        $this->information['typedAttributeCount'] = $this->information['publicTypedAttributes'] + $this->information['protectedTypedAttributes'] + $this->information['privateTypedAttributes'];
        $this->information['attributesPerClass']  = round( $this->information['attributeCount'] / $this->information['classCount'], 2 );
        $this->information['functionsPerClass']   = round( $this->information['functionCount'] / $this->information['classCount'], 2 );
        
        // Generate the needed text output
        return <<<END
Phuml generated statistics
==========================

General statistics
------------------

Classes:    {$this->information['classCount']}
Interfaces: {$this->information['interfaceCount']}

Attributes: {$this->information['attributeCount']} ({$this->information['typedAttributeCount']} are typed)
    * private:   {$this->information['privateAttributeCount']}
    * protected: {$this->information['protectedAttributeCount']}
    * public:    {$this->information['publicAttributeCount']}

Functions:  {$this->information['functionCount']} 
    * private:   {$this->information['privateFunctionCount']}
    * protected: {$this->information['protectedFunctionCount']}
    * public:    {$this->information['publicFunctionCount']}

Average statistics
------------------

Attributes per class: {$this->information['attributesPerClass']}
Functions per class:  {$this->information['functionsPerClass']}

END;
    }
}

?>
