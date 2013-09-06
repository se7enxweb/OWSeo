<?php

class OWSeoType extends eZDataType
{
    const DATA_TYPE_STRING = 'owseo';

    function __construct()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', 'OW SEO', 'Datatype name' ), array(
            'serialize_supported' => true
        ) );
    }

    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_owseometadata_data_array_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_owseometadata_data_array_' . $contentObjectAttribute->attribute( 'id' ) );
            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            if ( ! $classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() )
            {
                if ( $data == "" )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input required' ) );
                    return eZInputValidator::STATE_INVALID;
                }
                if ( empty( $data['title'] ) )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Title required' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_owseometadata_data_array_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_owseometadata_data_array_' . $contentObjectAttribute->attribute( 'id' ) );
            
            $meta = self::fillMetaData( $data );
            $contentObjectAttribute->setContent( $meta );
            return true;
        }
        return false;
    }

    /*!
     Does nothing since it uses the data_text field in the content object attribute.
     See fetchObjectAttributeHTTPInput for the actual storing.
    */
    function storeObjectAttribute( $attribute )
    {
    	if( $attribute->ID === null )
    	{
    		eZPersistentObject::storeObject( $attribute );
    	}

		$meta = $attribute->content();
    	$xmlString = self::saveXML( $meta );
        $attribute->setAttribute( 'data_text', $xmlString );
    }

    function fetchMetaData( $attribute )
    {
       try
       {
          $xml = new SimpleXMLElement( $attribute->attribute( 'data_text' ) );

          $meta = new owSeo( htmlspecialchars_decode( (string)$xml->title, ENT_QUOTES ),
                             htmlspecialchars_decode( (string)$xml->keywords, ENT_QUOTES ),
                             htmlspecialchars_decode( (string)$xml->description, ENT_QUOTES )
		  );
          return $meta;
       }
       catch ( Exception $e )
       {
           return new owSeo();
       }
    }
    
    function fillMetaData( $array )
    {
        return new owSeo( $array['title'], $array['keywords'], $array['description'] );
    }
    
    function objectAttributeContent( $attribute )
    {
        return self::fetchMetaData( $attribute );
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $attribute )
    {
        $meta = self::fetchMetaData( $attribute );
        return $meta->title .' '. $meta->keywords.' '. $meta->description;
    }

    function title( $attribute, $name = null )
    {
        $meta = self::fetchMetaData( $attribute );
        return $meta->title;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $meta = self::fetchMetaData( $contentObjectAttribute );
        if ( $meta instanceof self ) {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function fromString( $contentObjectAttribute, $string )
    {
        if ( $string != '' )
        {
            $contentObjectAttribute->setAttribute( 'data_text', $string );
            $meta = self::fetchMetaData( $contentObjectAttribute );
            $contentObjectAttribute->setContent( $meta );
        }
        return true;
    }

	function saveXML( $meta )
	{
    	$xml = new DOMDocument( "1.0", "UTF-8" );
        $xmldom = $xml->createElement( "MetaData" );
        $node = $xml->createElement( "title", htmlspecialchars( $meta->title, ENT_QUOTES, 'UTF-8' ) );
        $xmldom->appendChild( $node );
        $node = $xml->createElement( "keywords", htmlspecialchars( $meta->keywords , ENT_QUOTES, 'UTF-8' ) );
        $xmldom->appendChild( $node );
        $node = $xml->createElement( "description", htmlspecialchars( $meta->description, ENT_QUOTES, 'UTF-8' ) );
        $xmldom->appendChild( $node );
        $xml->appendChild( $xmldom );

        return $xml->saveXML();
	}

	function serializeContentObjectAttribute( $package, $objectAttribute )
	{
		$xmlString = self::saveXML( $objectAttribute->content() );
	    $DOMNode = $this->createContentObjectAttributeDOMNode( $objectAttribute );

	    if ( $xmlString != '' )
	    {
	    	$doc = new DOMDocument( '1.0', 'utf-8' );
	    	$success = $doc->loadXML( $xmlString );
	        $importedRootNode = $DOMNode->ownerDocument->importNode( $doc->documentElement, true );
	        $DOMNode->appendChild( $importedRootNode );
	     }
	    return $DOMNode;
	}

    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
	    foreach ( $attributeNode->childNodes as $childNode )
        {
            if ( $childNode->nodeType == XML_ELEMENT_NODE )
            {
                $xmlString = $childNode->ownerDocument->saveXML( $childNode );
                $objectAttribute->setAttribute( 'data_text', $xmlString );
                break;
            }
        }
    }
}

eZDataType::register( OWSeoType::DATA_TYPE_STRING, 'OWSeoType' );