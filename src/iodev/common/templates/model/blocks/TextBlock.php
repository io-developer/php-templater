<?php

namespace iodev\commons\templates\model\blocks;

use iodev\commons\templates\model\Block;
use iodev\commons\templates\model\BlockType;

/**
 * @author Sergey Sedyshev
 */
class TextBlock extends Block
{
    /**
     * @param string $content
     */
    public function __construct( $content="" )
    {
        $this->content = $content;
    }
    
    /** @var string */
    public $content = "";
    
    /**
     * @return string
     */
    public function type()
    {
        return BlockType::TEXT;
    }
    
    /**
     * @return string
     */
    public function toString()
    {
        return $this->content;
    }
}
