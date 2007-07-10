<?php

class plStructureWriterDot implements plStructureWriter 
{
    private $properties;

    private $output;

    private $structure;

    public function __construct() 
    {
        $this->properties = array( 
            'tableBorder'          => 1,
            'titleBackground'      => '#fcaf3e',
            'attributesBackground' => '#eeeeec',
            'functionsBackground'  => '#eeeeec',
            'titleColor'           => '#2e3436',
            'attributesColor'      => '#2e3436',
            'functionsColor'       => '#2e3436',
            'titleFont'            => 'Helvetica',
            'attributesFont'       => 'Helvetica',
            'functionsFont'        => 'Helvetica',
            'titleFontsize'        => 12,
            'attributesFontsize'   => 10,
            'functionsFontsize'    => 10,
        );

        $this->structure = null;
        $this->output = null;
    }

    public function writeStructure( $structure, $outfile ) 
    {
        $this->structure = $structure;

        $this->output = 'digraph "' . sha1( mt_rand() ) . '" {' . "\n";

        foreach( $structure as $object ) 
        {
            if ( $object instanceof plPhpClass ) 
            {
                $this->output .= $this->getClassDefinition( $object );
            } 
            else if ( $object instanceof plPhpInterface ) 
            {
                $this->output .= $this->getInterfaceDefinition( $object );
            }
        }

        $this->output .= "}";

        file_put_contents( $outfile, $this->output );
    }

    private function getClassDefinition( $o ) 
    {
        $def = '';

        // First we need to create the needed data arrays
        $name = $o->name;
        
        $attributes = array();
        foreach( $o->variables as $variable ) 
        {
            $attributes[] = $this->getModifierRepresentation( $variable->modifier ) . $variable->name;
        }

        $functions = array();
        foreach( $o->functions as $function ) 
        {
            $functions[] = $this->getModifierRepresentation( $function->modifier ) . $function->name . $this->getParamRepresentation( $function->params );
        }
        
        // Create the node
        $def .= $this->createNode( 
            $this->getUniqueId( $o ),
            array(
                'label' => $this->createLabel( $name, $attributes, $functions ),
                'shape' => 'plaintext',
            )
        );

        // Create class inheritance relation
        if ( $o->extends !== null ) 
        {
            // Check if we need an "external" class node
            if ( in_array( $o->extends, $this->structure ) !== true ) 
            {
                $def .= $this->getClassDefinition( $o->extends );
            }

            $def .= $this->createNodeRelation( 
                $this->getUniqueId( $o->extends ),
                $this->getUniqueId( $o ),
                array( 
                    'dir'       => 'back',
                    'arrowType' => 'normal',
                    'style'     => 'solid'
                )
            );
        }

        // Create class implements relation
        foreach( $o->implements as $interface ) 
        {
            // Check if we need an "external" interface node
            if ( in_array( $interface, $this->structure ) !== true ) 
            {
                $def .= $this->getInterfaceDefinition( $interface );
            }

            $def .= $this->createNodeRelation( 
                $this->getUniqueId( $interface ),
                $this->getUniqueId( $o ),
                array( 
                    'dir'       => 'back',
                    'arrowType' => 'empty',
                    'style'     => 'dashed',
                )
            );
        }

        return $def;
    }

    private function getInterfaceDefinition( $o ) 
    {
        $def = '';

        // First we need to create the needed data arrays
        $name = '{interface} ' . $o->name;
        
        $functions = array();
        foreach( $o->functions as $function ) 
        {
            $functions[] = $this->getModifierRepresentation( $function->modifier ) . $function->name . $this->getParamRepresentation( $function->params );
        }
        
        // Create the node
        $def .= $this->createNode( 
            $this->getUniqueId( $o ),
            array(
                'label' => $this->createLabel( $name, array(), $functions ),
                'shape' => 'plaintext',
            )
        );

        // Create interface inheritance relation        
        if ( $o->extends !== null ) 
        {
            // Check if we need an "external" interface node
            if ( in_array( $o->extends, $this->structure ) !== true ) 
            {
                $def .= $this->getInterfaceDefinition( $o->extends );
            }

            $def .= $this->createNodeRelation( 
                $this->getUniqueId( $o->extends ),
                $this->getUniqueId( $o ),
                array( 
                    'dir'       => 'back',
                    'arrowType' => 'normal',
                    'style'     => 'solid'
                )
            );
        }

        return $def;
    }

    private function getModifierRepresentation( $modifier ) 
    {
        return ( $modifier === 'public' )
               ? ( '+' )
               : ( ( $modifier === 'protected' )
                 ? ( '#' )
                 : ( '-' ) );
    }

    private function getParamRepresentation( $params ) 
    {
        if ( count( $params ) === 0 ) 
        {
            return '()';
        }

        $representation = '( ';
        for( $i = 0; $i<count( $params ); $i++ ) 
        {
            $representation .= $params[$i]->name;
            if ( $i < count( $params ) - 1 ) 
            {
                $representation .= ', ';
            }
        }
        $representation .= ' )';

        return $representation;
    }

    private function getUniqueId( $object ) 
    {
        return '"' . spl_object_hash( $object ) . '"';
    }

    private function createNode( $name, $options ) 
    {
        $node = $name . " [";
        foreach( $options as $key => $value ) 
        {
            $node .= $key . '=' . $value . ' ';
        }
        $node .= "]\n";
        return $node;
    }

    private function createNodeRelation( $node1, $node2, $options ) 
    {
        $relation = $node1 . ' -> ' . $node2 . ' [';
        foreach( $options as $key => $value ) 
        {
            $relation .= $key . '=' . $value . ' ';
        }
        $relation .= "]\n";
        return $relation;
    }

    private function createLabel( $name, $attributes, $functions )     
    {
        // Escape everything that shouldn't be there
        $this->escapeLabelData( &$name, &$attributes, &$functions );

        // Start the table
        $label = '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT">';
        
        // The title
        $label .= '<TR><TD BORDER="' . $this->tableBorder . '" ALIGN="CENTER" BGCOLOR="' . $this->titleBackground . '"><FONT COLOR="' . $this->titleColor . '" FACE="' . $this->titleFont . '" POINT-SIZE="' . $this->titleFontsize . '">' . $name . '</FONT></TD></TR>';

        // The attributes block
        $label .= '<TR><TD BORDER="' . $this->tableBorder . '" ALIGN="LEFT" BGCOLOR="' . $this->attributesBackground . '">';
        if ( count( $attributes ) === 0 ) 
        {
            $label .= ' ';
        }
        foreach( $attributes as $attribute ) 
        {
            $label .= '<FONT COLOR="' . $this->attributesColor . '" FACE="' . $this->attributesFont . '" POINT-SIZE="' . $this->attributesFontsize . '">' . $attribute . '</FONT><BR ALIGN="LEFT"/>';
        }
        $label .= '</TD></TR>';

        // The function block
        $label .= '<TR><TD BORDER="' . $this->tableBorder . '" ALIGN="LEFT" BGCOLOR="' . $this->functionsBackground . '">';
        if ( count( $functions ) === 0 ) 
        {
            $label .= ' ';
        }
        foreach( $functions as $function ) 
        {
            $label .= '<FONT COLOR="' . $this->functionsColor . '" FACE="' . $this->functionsFont . '" POINT-SIZE="' . $this->functionsFontsize . '">' . $function . '</FONT><BR ALIGN="LEFT"/>';
        }
        $label .= '</TD></TR>';

        // End the table
        $label .= '</TABLE>>';

        return $label;
    }

    private function escapeLabelData( $name, $attributes, $functions ) 
    {
        /*
        $characters = array( 
            '$',
        );

        $replacement = array( 
            '$',
        );

        $name = str_replace( $characters, $replacement, $name );

        foreach( $attributes as $key => $value ) 
        {
            $attributes[$key] = str_replace( $characters, $replacement, $value );
        }

        foreach( $functions as $key => $value ) 
        {
            $functions[$key] = str_replace( $characters, $replacement, $value );
        }
        */
    }

    public function __get( $key )
    {
        if ( !array_key_exists( $key, $this->properties ) )
        {
            throw new plBasePropertyException( $key, plBasePropertyException::READ );
        }
        return $this->properties[$key];
    }

    public function __set( $key, $val )
    {
        if ( !array_key_exists( $key, $this->properties ) )
        {
            throw new plBasePropertyException( $key, plBasePropertyException::WRITE );
        }
        $this->properties[$key] = $val;            
    }
}

?>
