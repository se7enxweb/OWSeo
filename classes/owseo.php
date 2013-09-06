<?php

class owSeo extends ezcBaseStruct {
    public $title;
    public $keywords;
    public $description;

    public function __construct( $title = false, $keywords = false, $description = false ) {
        $this->title = $title;
        $this->keywords = $keywords;
        $this->description = $description;
    }

    function hasAttribute( $name ) {
        $classname = get_class( $this );
        $vars = get_class_vars( $classname );
        if ( array_key_exists( $name, $vars ) )
            return true;
        else
            return false;
    }

    function attribute( $name ) {
        return $this->$name;
    }
}
?>