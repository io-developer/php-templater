<?php

namespace iodev\commons\templates\model\blocks;

use iodev\commons\templates\model\Block;
use iodev\commons\templates\model\BlockType;

/**
 * @author Sergey Sedyshev
 */
class RenderIncludedBlock extends Block
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
    
    /** @var NodeBlock */
    public $renderedRoot;

    /**
     * @return string
     */
    public function type()
    {
        return BlockType::RENDER_INCLUDED;
    }
    
    /**
     * @return string
     */
    public function toString()
    {
        if ($this->rendered && $this->renderedRoot) {
            return $this->renderedRoot->toString();
        }
        return "";
    }
}
