<?php

abstract class plGraphvizProcessorStyle 
{
    public static function factory( $style ) 
    {
        $classname = 'plGraphvizProcessor' . ucfirst( $style ) . 'Style';
        if ( class_exists( $classname ) === false ) 
        {
            throw new plGraphvizProcessorStyleNotFoundException( $style );
        }
        return new $classname();
    }

    public $interfaceTableBorder            = 1;
    public $interfaceTitleBackground        = '#729fcf';
    public $interfaceAttributesBackground   = '#eeeeec';
    public $interfaceFunctionsBackground    = '#eeeeec';
    public $interfaceTitleColor             = '#2e3436';
    public $interfaceAttributesColor        = '#2e3436';
    public $interfaceFunctionsColor         = '#2e3436';
    public $interfaceTitleFont              = 'Helvetica';
    public $interfaceAttributesFont         = 'Helvetica';
    public $interfaceFunctionsFont          = 'Helvetica';
    public $interfaceTitleFontsize          = 12;
    public $interfaceAttributesFontsize     = 10;
    public $interfaceFunctionsFontsize      = 10;

    public $classTableBorder                = 1;
    public $classTitleBackground            = '#fcaf3e';
    public $classAttributesBackground       = '#eeeeec';
    public $classFunctionsBackground        = '#eeeeec';
    public $classTitleColor                 = '#2e3436';
    public $classAttributesColor            = '#2e3436';
    public $classFunctionsColor             = '#2e3436';
    public $classTitleFont                  = 'Helvetica';
    public $classAttributesFont             = 'Helvetica';
    public $classFunctionsFont              = 'Helvetica';
    public $classTitleFontsize              = 12;
    public $classAttributesFontsize         = 10;
    public $classFunctionsFontsize          = 10;
}
