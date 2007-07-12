<?php

class plStructureGeneratorTokenizer extends plStructureGenerator 
{
    private $classes;
    private $interfaces;

    private $class;
    private $interface;
    private $variables;
    private $functions;
    private $implements;
    private $extends;
    private $modifier;

    public function __construct() 
    {
        $this->initGlobalAttributes();
        $this->initParserAttributes();
    }

    private function initGlobalAttributes() 
    {
        $this->classes      = array();
        $this->interfaces   = array();
    }

    private function initParserAttributes() 
    {
        $this->class        = null;
        $this->interface    = null;
        $this->function     = null;
        $this->variables    = array();
        $this->functions    = array();
        $this->params       = array();
        $this->implements   = array();
        $this->extends      = null;
        $this->modifier     = 'public';
        
        $this->lastToken    = null;
    }

    public function createStructure( array $files ) 
    {
        $this->initGlobalAttributes();

        foreach( $files as $file ) 
        {
            $this->initParserAttributes();
            $tokens = token_get_all( file_get_contents( $file ) );

            // Loop through all tokens
            foreach( $tokens as $token ) 
            {
                // Skip all whitespaces
                if ( is_array( $token ) === true && $token[0] === T_WHITESPACE ) 
                {
                    continue;
                }
                // Skip commas as in implements statements
                else if ( is_array( $token ) !== true && $token === ',' ) 
                {
                    continue;
                }
                // Skip opening brackets
                else if ( is_array( $token ) !== true && $token === '(' ) 
                {
                    continue;
                }
                // Skip T_VAR, T_ARRAY after T_FUNCTION (function typehints)
                else if ( is_array( $token ) === true && $this->lastToken === T_FUNCTION && ( $token[0] === T_VAR || $token[0] === T_ARRAY ) ) 
                {
                    continue;
                }
                // Skip = after T_FUNCTION (function defaults)
                else if ( is_array( $token ) !== true && $this->lastToken === T_FUNCTION && ( $token === '=' ) ) 
                {
                    continue;
                }
                // Skip stuff after T_FUNCTION (function defaults)
                else if ( is_array( $token ) === true && $this->lastToken === T_FUNCTION && ( $token[0] === T_CONSTANT_ENCAPSED_STRING || $token[0] === T_LNUMBER || $token[0] === T_DNUMBER || $token[0] === T_PAAMAYIM_NEKUDOTAYIM ) ) 
                {
                    continue;
                }
                // New intial token of interest 
                else if ( $this->lastToken === null && is_array( $token ) === true ) 
                {
                    $this->initialToken( $token );
                }
                // We found a string token. Isolate information based on the last token found
                else if ( is_array( $token ) === true && $token[0] === T_STRING ) 
                {
                    $this->stringToken( $token );
                } 
                // T_Variable
                else if ( is_array( $token ) === true && $token[0] === T_VARIABLE ) 
                {
                    $this->variableToken( $token );
                }
                // Closing bracket
                else if ( is_array( $token ) !== true && $token === ')' ) 
                {
                    $this->closingBracket( $token );
                }
                // T_FUNCTION
                else if ( is_array( $token ) === true && $token[0] === T_FUNCTION ) 
                {
                    $this->functionToken( $token );
                }
                // There is a new token we do not know and don't want to know
                else 
                {
                    $this->lastToken = null;
                }
            }

            // Store interface or class in parser arrays
            $this->storeClassOrInterface();
        }

        // Fix the class and interface connections
        $this->fixObjectConnections();

        // Return the class and interface structure
        return array_merge( $this->classes, $this->interfaces );
    }

    private function initialToken( $token ) 
    {
        switch ( $token[0] ) 
        {
            case T_INTERFACE:                        
            case T_CLASS:
                $this->storeClassOrInterface(); 
                $this->lastToken = $token[0];
            break;
            case T_IMPLEMENTS:
            case T_EXTENDS:
                $this->lastToken = $token[0];
            break;
            case T_PUBLIC:
            case T_PROTECTED:
            case T_PRIVATE:
                $this->modifier  = $token[1];
                $this->lastToken = $token[0];
            break;
            case T_FUNCTION:
                $this->lastToken = $token[0];
            break;
        }
    }

    private function stringToken( $token ) 
    {
        switch( $this->lastToken ) 
        {
            case T_IMPLEMENTS:
                // Add interface to implements array
                $this->implements[] = $token[1];
                // We do not reset the last token here, because there might be multiple interfaces
            break;
            case T_EXTENDS:
                // Set the superclass
                $this->extends = $token[1];
                // Reset the last token
                $this->lastToken = null;
            break;                        
            case T_FUNCTION:
                // Add the current function only if there is no function name already
                // Because if we know the function name already this is a type hint
                if ( $this->function === null ) 
                {
                    $this->function = $token[1];                           
                }
            break;
            case T_CLASS:
                // Set the class name
                $this->class = $token[1];
                // Reset the last token
                $this->lastToken = null;
            break;
            case T_INTERFACE:
                // Set the interface name
                $this->interface = $token[1];
                // Reset the last Token
                $this->lastToken = null;
            break;
        }
    }

    private function variableToken( $token ) 
    {
        switch( $this->lastToken ) 
        {
            case T_PUBLIC:
            case T_PROTECTED:
            case T_PRIVATE:
                $this->variables[] = new plPhpVariable( $token[1], $this->modifier );
                $this->lastToken = null;
                $this->modifier = 'public';
            break;
            case T_FUNCTION:
                $this->params[] = new plPhpVariable( $token[1] );
            break;
        }
    }

    private function closingBracket( $token ) 
    {
        switch ( $this->lastToken ) 
        {
            case T_FUNCTION:
                // Add the current function
                $this->functions[] = array( $this->function, $this->modifier, $this->params );                           
                // Reset the last token
                $this->lastToken = null;
                //Reset the modifier state
                $this->modifier = 'public';
                // Reset the params array
                $this->params = array();
                // Reset the function name
                $this->function = null;            
            break;
        }
    }

    private function functionToken( $token ) 
    {
        switch( $this->lastToken ) 
        {
            case T_PUBLIC:
            case T_PROTECTED:
            case T_PRIVATE:
                $this->lastToken = $token[0];
            break;
        }
    }

    private function storeClassOrInterface() 
    {
        // First we need to check if we should store interface data found so far
        if ( $this->interface !== null ) 
        {
            // Init data storage
            $functions = array();

            // Create the data objects
            foreach( $this->functions as $function ) 
            {
                $functions[] = new plPhpFunction( $function[0], $function[1], $function[2] );                                    
            }
            $interface = new plPhpInterface( $this->interface, $functions, $this->extends );                              
            
            $this->interfaces[$this->interface] = $interface;
        }
        else if ( $this->class !== null ) 
        {
            // Init data storage
            $functions = array();

            // Create the data objects
            foreach( $this->functions as $function ) 
            {
                $functions[] = new plPhpFunction( $function[0], $function[1], $function[2] );                                    
            }
            $class = new plPhpClass( $this->class, $this->variables, $functions, $this->implements, $this->extends );                              
            
            $this->classes[$this->class] = $class;
        }

        $this->initParserAttributes();
    }

    private function fixObjectConnections() 
    {
        foreach( $this->classes as $class ) 
        {
            $implements = array();
            foreach( $class->implements as $key => $impl ) 
            {
                $implements[$key] = array_key_exists( $impl, $this->interfaces ) 
                                    ? $this->interfaces[$impl]
                                    : $this->interfaces[$impl] = new plPhpInterface( $impl );
            }
            $class->implements = $implements;

            if ( $class->extends === null ) 
            {
                continue;
            }
            $class->extends = array_key_exists( $class->extends, $this->classes ) 
                              ? $this->classes[$class->extends] 
                              : $this->classes[$class->extends] = new plPhpClass( $class->extends );
        }
        foreach( $this->interfaces as $interface ) 
        {           
            if ( $interface->extends === null ) 
            {
                continue;
            }
            $interface->extends = array_key_exists( $interface->extends, $this->interfaces ) 
                                 ? $this->interfaces[$interface->extends] 
                                 : $this->interfaces[$interface->extends] = new plPhpInterface( $interface->extends );
        }
    }
}

?>
