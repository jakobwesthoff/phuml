<?php

class plStructureGeneratorTokenizer implements plStructureGenerator 
{
    public function createGraph( array $files ) 
    {
        $classes    = array();
        $interfaces = array();

        foreach( $files as $file ) 
        {
            // Initialize the storage arrays
            $class      = null;
            $interface  = null;
            $variables  = array();
            $functions  = array();
            $implements = array();
            $extends    = null;
            $modifier   = 'public';

            $tokens = token_get_all( file_get_contents( $file ) );
            $lastToken = null;

            // Loop through all tokens
            foreach( $tokens as $token ) 
            {
                // Skip all whitespaces
                if ( is_array( $token ) === true && $token[0] === T_WHITESPACE ) 
                {
                    continue;
                }
                // New token of interest 
                else if ( $lastToken === null && is_array( $token ) === true ) 
                {
                    switch ( $token[0] ) 
                    {
                        case T_CLOSE_TAG:
                            // First we need to check if we should store the data found so far
                            if ( $interface !== null ) 
                            {
                                // Init data storage
                                $f = array();

                                // Create the data objects
                                foreach( $functions as $function ) 
                                {
                                    $f[] = new plPhpFunction( $function[0], $function[1] );                                    
                                }
                                $i = new plPhpInterface( $interface, $f, $extends );                              
                                
                                $interfaces[$interface] = $i;
                            }

                            // First we need to check if we should store the data found so far
                            if ( $class !== null ) 
                            {
                                // Init data storage
                                $f = array();
                                $a = array();                              

                                // Create the data objects
                                foreach( $functions as $function ) 
                                {
                                    $f[] = new plPhpFunction( $function[0], $function[1] );                                    
                                }
                                foreach( $variables as $attribute ) 
                                {
                                    $a[] = new plPhpAttribute( $attribute[0], $attribute[1] );                                    
                                }
                                $c = new plPhpClass( $class, $a, $f, $implements, $extends );                              
                                
                                $classes[$class] = $c;
                            }
                        break;
                        case T_INTERFACE:                        
                            // First we need to check if we should store the data found so far
                            if ( $interface !== null ) 
                            {
                                // Init data storage
                                $f = array();

                                // Create the data objects
                                foreach( $functions as $function ) 
                                {
                                    $f[] = new plPhpFunction( $function[0], $function[1] );                                    
                                }
                                $i = new plPhpInterface( $interface, $f, $extends );                              
                                
                                $interfaces[$interface] = $i;
                            }

                            $class      = null;
                            $interface  = null;
                            $variables  = array();
                            $functions  = array();
                            $implements = array();
                            $extends    = null;
                            $modifier   = 'public';

                            $lastToken = $token[0];
                        break;
                        case T_CLASS:
                            // First we need to check if we should store the data found so far
                            if ( $class !== null ) 
                            {
                                // Init data storage
                                $f = array();
                                $a = array();                              

                                // Create the data objects
                                foreach( $functions as $function ) 
                                {
                                    $f[] = new plPhpFunction( $function[0], $function[1] );                                    
                                }
                                foreach( $variables as $attribute ) 
                                {
                                    $a[] = new plPhpAttribute( $attribute[0], $attribute[1] );                                    
                                }
                                $c = new plPhpClass( $class, $a, $f, $implements, $extends );                              
                                
                                $classes[$class] = $c;
                            }

                            $class      = null;
                            $interface  = null;
                            $variables  = array();
                            $functions  = array();
                            $implements = array();
                            $extends    = null;
                            $modifier   = 'public';
                            
                            $lastToken = $token[0];
                        break;
                        case T_IMPLEMENTS:
                        case T_EXTENDS:
                            $lastToken = $token[0];
                        break;
                        case T_PUBLIC:
                        case T_PROTECTED:
                        case T_PRIVATE:
                            $modifier  = $token[1];
                            $lastToken = null;
                        break;
                        case T_FUNCTION:
                            $lastToken = $token[0];
                        break;
                    }
                }
                // Skip commas as in implements statements
                else if ( is_array( $token ) !== true && $token === ',' ) 
                {
                    continue;
                }
                // We found a string token. Isolate information based on the last token found
                else if ( is_array( $token ) === true && $token[0] === T_STRING ) 
                {
                    switch( $lastToken ) 
                    {
                        case T_IMPLEMENTS:
                            $implements[] = $token[1];
                        break;
                        case T_EXTENDS:
                            $extends = $token[1];
                            $lastToken = null;
                        break;                        
                        case T_FUNCTION:
                            $functions[] = array( $token[1], $modifier );                           
                            $lastToken = null;
                            $modifier = 'public';
                        break;
                        case T_CLASS:
                            $class = $token[1];
                            $lastToken = null;
                        break;
                        case T_INTERFACE:
                            $interface = $token[1];
                            $lastToken = null;
                        break;
                    }

                } 
                else 
                {
                    $lastToken = null;
                }
            }
        }

        // Fix the class and interface connections
        foreach( $classes as $class ) 
        {
            $implements = array();
            foreach( $class->implements as $key => $impl ) 
            {
                $implements[$key] = array_key_exists( $impl, $interfaces ) ? $interfaces[$impl]: new plPhpInterface( $impl );
            }
            $class->implements = $implements;

            if ( $class->extends === null ) 
            {
                continue;
            }
            $class->extends = array_key_exists( $class->extends, $classes ) ? $classes[$class->extends] : new plPhpClass( $class->extends );
        }
        foreach( $interfaces as $interface ) 
        {           
            if ( $interface->extends === null ) 
            {
                continue;
            }
            $interface->extends = array_key_exists( $class->extends, $classes ) ? $classes[$class->extends] : new plPhpClass( $class->extends );
        }

        // Return the class and interface structure
        return array_merge( $classes, $interfaces );
    }
}

?>
