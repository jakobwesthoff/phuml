<?php

define('XML_OPTIONS', LIBXML_DTDLOAD | LIBXML_NOENT | LIBXML_DTDATTR | LIBXML_NOCDATA);
 
$source = parse_tree_from_file('test.php');
/*
Tree modifications...
*/

$xml = new DOMDocument;
$xml->preserveWhiteSpace = false;
$xml->loadXML($source, XML_OPTIONS);
 
$xml->formatOutput = true;
$xml->save( 'output.xml' );

$xsl = new DOMDocument;
$xsl->load('toWrite.xsl', XML_OPTIONS);
 
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl);
file_put_contents('output.php', $proc->transformToXML($xml));


$xml = simplexml_load_string( $source );
$x = $xml->children( "http://php.net/xsl" );

foreach( $x->xpath( '//php:class_declaration_statement' ) as $class ) 
{
    $name = array_shift( $class->xpath( '*/php:T_STRING' ) );
    $extends = array_shift( $class->xpath( '*/php:extends_from/php:fully_qualified_class_name/php:T_STRING' ) );
    echo "class ", $name, " extends ", $extends, "\n";

    foreach( $class->xpath( '*//php:class_statement' ) as $statement ) 
    {
        $isfunction = true;
        if ( array_shift( $statement->xpath( 'php:T_FUNCTION' ) )  === null ) 
        {
            $isfunction = false;
            if ( ( $vname = array_shift( $statement->xpath( 'php:class_variable_declaration/php:T_VARIABLE' ) ) ) === null ) 
            {
                continue;
            }
        }

        $modifier = array_shift( $statement->xpath( '*//php:member_modifier' ) );
        if ( $modifier !== null ) 
        {
            $m = "";
            switch ( true ) 
            {
                case ( $m = array_shift( $modifier->xpath( 'php:T_PUBLIC' ) ) ) !== null:
                break;
                case ( $m = array_shift( $modifier->xpath( 'php:T_PROTECTED' ) ) ) !== null:
                break;
                case ( $m = array_shift( $modifier->xpath( 'php:T_PRIVATE' ) ) ) !== null:
                break;
            }
        }
        else 
        {
            $m = "public";
        }
        if ( $isfunction ) 
        {
            $fname = array_shift( $statement->xpath( 'php:T_STRING' ) );
            echo "    $m function $fname\n";
        }
        else 
        {
            echo "    $m $vname\n";
        }
    }
}

?>
