<?php

namespace iodev\commons\templates\model;

use iodev\commons\templates\model\blocks\AssetBlock;
use iodev\commons\templates\model\blocks\AssetContainerBlock;
use iodev\commons\templates\model\blocks\NodeBlock;
use iodev\commons\templates\model\blocks\RenderBlock;
use iodev\commons\templates\model\blocks\RenderIncludedBlock;
use iodev\commons\templates\model\blocks\SuperBlock;
use iodev\commons\templates\model\blocks\TextBlock;

/**
 * @author Sergey Sedyshev
 */
class NodeBuilderUnit
{
    /**
     * @param IBuffer $buffer
     * @param NodeBuilderUnit $parent
     * @param string $blockName
     */
    public function __construct( IBuffer $buffer, $parent=null, $blockName=null )
    {
        $this->_buffer = $buffer;
        $this->parent = $parent;
        $this->block = new NodeBlock($blockName);
    }
    
    
    /** @var NodeBuilderUnit */
    public $parent;
    
    /** @var NodeBlock */
    public $block;
    
    /** @var IBuffer */
    private $_buffer;
    
    
    /**
     * @return NodeBuilderUnit
     */
    public function start()
    {
        if ($this->parent) {
            $this->parent->dumpBuffer();
        }
        return $this;
    }
    
    /**
     * @return NodeBuilderUnit
     */
    public function end()
    {
        $this->dumpBuffer();
        return $this->parent ? $this->parent : $this;
    }
    
    /**
     * @param string $name
     * @return NodeBuilderUnit
     */
    public function startChild( $name )
    {
        $unit = new NodeBuilderUnit($this->_buffer, $this, $name);
        $this->pushBlock($unit->block);
        return $unit->start();
    }
    
    /**
     * @return NodeBuilderUnit
     */
    public function dumpBuffer()
    {
        $s = $this->_buffer->snapshot();
        if (strlen($s) > 0) {
            $this->block->children[] = new TextBlock($s);
        }
        return $this;
    }
    
    /**
     * @param Block $block
     * @return NodeBuilderUnit
     */
    public function pushBlock( $block )
    {
        $this->dumpBuffer();
        $this->block->children[] = $block;
        return $this;
    }
    
    /**
     * @return NodeBuilderUnit
     */
    public function pushSuper()
    {
        return $this->pushBlock(new SuperBlock());
    }
    
    /**
     * @return NodeBuilderUnit
     */
    public function pushRender( $path, $params )
    {
        return $this->pushBlock(new RenderBlock($path, $params));
    }
    
    /**
     * @param string $path
     * @param array $params
     * @return NodeBuilderUnit
     */
    public function pushRenderIncluded( $path, $params )
    {
        return $this->pushBlock(new RenderIncludedBlock($path, $params));
    }
    
    /**
     * @param Asset $asset
     * @return NodeBuilderUnit
     */
    public function pushAsset( Asset $asset )
    {
        return $this->pushBlock(new AssetBlock($asset));
    }
    
    /**
     * @param string $type
     * @return NodeBuilderUnit
     */
    public function pushAssetContainer( $type )
    {
        return $this->pushBlock(new AssetContainerBlock($type));
    }
}
