<?php

namespace iodev\commons\templates\model\blocks;

use iodev\commons\templates\model\Block;
use iodev\commons\templates\model\BlockType;

/**
 * @author Sergey Sedyshev
 */
class NodeBlock extends Block
{
    /**
     * @param string $name
     */
    public function __construct( $name="" )
    {
        $this->name = $name;
    }


    /** @var string */
    public $name;
    
    /** @var Block[] */
    public $children = [];
    
    
    /**
     * @return string
     */
    public function type()
    {
        return BlockType::NODE;
    }
    
    /**
     * @param Block[] $children
     * @return NodeBlock
     */
    public function addChildren( $children )
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }
        return $this;
    }

    /**
     * @param Block $child
     * @return NodeBlock
     */
    public function addChild( Block $child )
    {
        $this->children[] = $child;
        return $this;
    }
    
    /**
     * @return string
     */
    public function toString()
    {
        $s = "";
        foreach ($this->children as $child) {
            $s .= $child->toString();
        }
        return $s;
    }
}
