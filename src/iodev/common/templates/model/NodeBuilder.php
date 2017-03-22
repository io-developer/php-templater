<?php

namespace iodev\commons\templates\model;


/**
 * @author Sergey Sedyshev
 */
class NodeBuilder
{
    public function __construct( IBuffer $buffer )
    {
        $this->_buffer = $buffer;
    }
    
    
    /** @var IBuffer */
    private $_buffer;
    
    /** @var NodeBuilderUnit */
    private $_rootUnit;
    
    /** @var NodeBuilderUnit */
    private $_unit;
    
    /** @var Node */
    private $_node;
    
    
    /**
     * @return Node
     */
    public function toNode()
    {
        return $this->_node;
    }

    /**
     * @return NodeBuilder
     */
    public function start()
    {
        $this->_rootUnit = new NodeBuilderUnit($this->_buffer);
        $this->_node = new Node($this->_rootUnit->block);
        $this->_unit = $this->_rootUnit;
        $this->_buffer->end();
        $this->_buffer->start();
        return $this;
    }
    
    /**
     * @return NodeBuilder
     */
    public function end()
    {
        $this->_unit->end();
        $this->_rootUnit->end();
        $this->_buffer->end();
        $this->_rootUnit = null;
        $this->_unit = null;
        return $this;
    }
    
    /**
     * @return type
     */
    public function endGetNode()
    {
        $this->end();
        return $this->toNode();
    }
    
    
    /**
     * @param string $path
     * @return NodeBuilder
     */
    public function extend( $path )
    {
        $this->_node->extendPath = $path;
        return $this;
    }
    
    /**
     * @param string $name
     * @return NodeBuilder
     */
    public function block( $name )
    {
        $this->_unit = $this->_unit->startChild($name);
        return $this;
    }
    
    /**
     * @return NodeBuilder
     */
    public function endblock()
    {
        $this->_unit = $this->_unit->end();
        return $this;
    }
    
    /**
     * @return NodeBuilder
     */
    public function superblock()
    {
        $this->_unit->pushSuper();
        return $this;
    }
    
    /**
     * @param string $path
     * @param array $params
     * @return NodeBuilder
     */
    public function render( $path, $params )
    {
        $this->_unit->pushRender($path, $params);
        return $this;
    }
    
    /**
     * @param string $path
     * @param array $params
     * @return NodeBuilder
     */
    public function renderIncluded( $path, $params )
    {
        $this->_unit->pushRenderIncluded($path, $params);
        return $this;
    }
    
    /**
     * @param string $type
     * @return NodeBuilder
     */
    public function requiredAssets( $type=null )
    {
        $this->_unit->pushAssetContainer($type);
        return $this;
    }
    
    /**
     * @param Asset $asset
     * @return NodeBuilder
     */
    public function requireAsset( Asset $asset )
    {
        $this->_unit->pushAsset($asset);
        return $this;
    }
}
