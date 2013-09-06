<?php 

class SEOOperator {
	
	var $Operators;
	
	function __construct() {
		$this->Operators = array_keys($this->namedParameterList());
	}
	
	function &operatorList() {
		return $this->Operators;
	}
	
	function namedParameterPerOperator() {
		return true;
	}
	
	function namedParameterList() {
	    return array (
    		'seo_parse' => array(
    			'class_identifier' => array(
    				'type' => 'string',
    				'required' => true,
    				'default' => null,
    			),
    			'node_id' => array(
    				'type' => 'int',
    				'required' => false,
    				'default' => null,
    			),
    		),
    		'seo_metadata' => array(
    			'node_id' => array(
    				'type' => 'int',
    				'required' => true,
    				'default' => null,
    			),
    		),
		);
	}
	
	function modify ( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters ) {
		
		switch ($operatorName) {			
			case 'seo_metadata' :
				$node_id = $namedParameters['node_id'];
				$operatorValue = $this->seoMetaData($node_id);
				break;
			
			case 'seo_parse' :
				$class_identifier = $namedParameters['class_identifier'];
				$node_id = isset($namedParameters['node_id']) ? $namedParameters['node_id'] : '';
				$operatorValue = $this->seoParse($class_identifier, $node);
				break;
		}
	}	
	
	/*********************************************************************
	 OPERATORS
	*********************************************************************/

	function seoMetaData( $node_id ) {
		
		$node = eZContentObjectTreeNode::fetch( $node_id );
		
		if ( $node instanceof eZContentObjectTreeNode ) {
			return $this->seoParse($node->ClassIdentifier, $node);
		}
		return false;
	}
	
	/*
	 * With parameter "node" => return data of the node with variables replaced (view mode)
	 * Without parameter "node" => return data mask with variables like '[[name]]' (edit mode) 
	 */
	function seoParse($classIdentifier, $node = null) {
		
		$iniSeo 		= eZIni::instance('owseo.ini');
		$seoTypes 		= array('title', 'keywords', 'description');
		$nodeValues 	= array();
		$rules 			= array();
		$seoValues 		= array();
		$dataMap 		= array();
		
		if ($iniSeo->hasVariable('Rules', 'Rule_' . $classIdentifier)) {
			$rules = $iniSeo->variable('Rules', 'Rule_' . $classIdentifier);
		}
		
		if ( isset($node) && $node instanceof eZContentObjectTreeNode ) {
			$dataMap = $node->dataMap();
			foreach ( $dataMap as $attribute ) {
				if ( $attribute->DataTypeString == OWSeoType::DATA_TYPE_STRING ) {
					$content = $attribute->content();
					foreach ($seoTypes as $seoType) {
						$nodeValues[ $seoType ] = $content->{$seoType};
					}
				}
			}
		}
		
		foreach ( $seoTypes as $seoType) {
			if ( isset($nodeValues[ $seoType ]) && $nodeValues[ $seoType ] ) {
				$seoValues[ $seoType ] = $nodeValues[ $seoType ];
			} elseif ( isset($rules[ $seoType ] ) ) {
				$seoString = $rules[ $seoType ];
				foreach ( array_keys($dataMap) as $attributeName ) {
					if (strpos($seoString, '[[' . $attributeName . ']]') !== false) {
						$seoString = str_replace('[[' . $attributeName . ']]', $dataMap[ $attributeName ]->content(), $seoString);
					}
				}
				
				$seoValues[ $seoType ] = $seoString;
			} else {
				$seoValues[ $seoType ] = '';
			}
		}
		return $seoValues;
	}
}