<?php

namespace iodev\commons\templates\model;

/**
 * @author Sergey Sedyshev
 */
class Asset
{
    /**
     * @param string $type
     * @param string $path
     * @param array $options
     */
    public function __construct( $type, $path, $options=null )
    {
        $this->type = $type;
        $this->path = $path;
        $this->options = $options ? $options : [];
    }
    
    
    /** @var string */
    public $type;
    
    /** @var string */
    public $path;
    
    /** @var array */
    public $options;
    
}
