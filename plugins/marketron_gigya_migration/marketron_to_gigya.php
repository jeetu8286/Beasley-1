<?php

include 'tidyjson.php';

define( 'INPUT_FILE', 'marketron_export_20140723.xml' );
define( 'XSLT_DOC', 'marketron_to_gigya.xsl' );

// Load the XML source
$xml = new DOMDocument;
$xml->load( INPUT_FILE );

$xsl = new DOMDocument;
$xsl->load( XSLT_DOC );

// Configure the transformer
$proc = new XSLTProcessor;
$proc->importStyleSheet( $xsl ); // attach the xsl rules

$transformed_document = $proc->transformToXML( $xml );
//echo $transformed_document ;
echo TidyJSON::tidy( $transformed_document );

