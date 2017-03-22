<?php

namespace iodev\commons\templates\model\blocks;

use iodev\commons\templates\model\Block;
use iodev\commons\templates\model\BlockType;

/**
 * @author Sergey Sedyshev
 */
class RenderBlock extends Block
{
    /**
     * @param string $path
     * @param array $params
     */
    public function __construct( $path, $params )
    {
        $this->path = $path;
        $this->params = $params;
    }


    /** @var string */
    public $path;
    
    /** @var mixed[] */
    public $params;
    
    /** @var bool */
    public $rendered = false;
    
    /** @var string */
    public $renderedContent = "";
    
    /** @var array */
    public $requiredAssets = [];
    
    /**
     * @return string
     */
    public function type()
    {
        return BlockType::RENDER;
    }
    
    /**
     * @return string
     */
    public function toString()
    {
        return $this->rendered ? $this->renderedContent : "";
    }
}
