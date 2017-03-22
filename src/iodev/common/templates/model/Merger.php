<?php

namespace iodev\commons\templates\model;

use iodev\commons\templates\model\blocks\NodeBlock;
use iodev\commons\templates\model\blocks\RenderIncludedBlock;
use iodev\commons\templates\model\BlockType;

/**
 * @author Sergey Sedyshev
 */
class Merger
{
    public function __construct( ICloner $cloner )
    {
        $this->_cloner = $cloner;
    }
    
    
    /** @var ICloner */
    private $_cloner;
    
    /** @var NodeBlock */
    private $_root;
    
    /** @var MergeData[] */
    private $_namedBlockDatas;
    
    
    /**
     * @param NodeBlock[] $rootBlocks
     * @return NodeBlock
     */
    public function mergeBlocks( $rootBlocks )
    {
        $b = new NodeBlock();
        $b->addChildren($rootBlocks);
        return $this->mergeBlock($b);
    }
    
    /**
     * @param NodeBlock $root
     * @return NodeBlock
     */
    public function mergeBlock( NodeBlock $root )
    {
        $this->_namedBlockDatas = [];
        $this->_root = $this->_cloner->cloneBlock($root);
        $this->_handleNodeBlock($this->_root);
        $this->_finalize();
        return $this->_root;
    }
    
    /**
     * @param MergeData $data
     */
    private function _handleBlock( MergeData $data )
    {
        switch ($data->block->type()) {
            case BlockType::NODE:
                $this->_handleNodeBlock($data->block, $data);
                break;
            
            case BlockType::SUPER:
                $this->_handleSuperBlock($data);
                break;
            
            case BlockType::RENDER_INCLUDED:
                $this->_handleRenderIncludedBlock($data);
                break;
        }
    }
    
    /**
     * @param MergeData $data
     */
    private function _handleRenderIncludedBlock( MergeData $data )
    {
        /* @var $b RenderIncludedBlock */
        $b = $data->block;
        if ($b->renderedRoot) {
            $this->_handleNodeBlock($b->renderedRoot);
        }
    }
    
    /**
     * @param MergeData $data
     */
    private function _handleSuperBlock( MergeData $data )
    {
        $super = $data->super;
        if ($super && !$super->usedAsSuper) {
            $super->usedAsSuper = true;
            $data->parent->children[$data->index] = $super->block;
        } else {
            unset($data->parent->children[$data->index]);
        }
    }
    
    /**
     * @param NodeBlock $block
     * @param MergeData $data
     */
    private function _handleNodeBlock( NodeBlock $block, $data=null )
    {
        $isNamed = (bool)($data && isset($block->name));
        if ($isNamed) {
            $data = $this->_handleNamed($block->name, $data);
            $super = $data->super;
            $this->_namedDataInc($block->name, 1);
        }
        foreach ($block->children as $k => $child) {
            $this->_handleBlock(new MergeData($child, $block, $k, $super));
        }
        if ($isNamed) {
            $this->_namedDataInc($block->name, -1);
        }
    }
    
    /**
     * @param string $name
     * @param MergeData $data
     * @return MergeData
     */
    private function _handleNamed( $name, MergeData $data )
    {
        $d = clone $data;
        
        $super = $this->_namedBlockDatas[$name];
        if ($super) {
            $d->openCount = $super->openCount;
        }
        
        if ($d->openCount === 0) {
            $d->super = $super;
            $this->_namedBlockDatas[$name] = $d;
        }
        
        return $d;
    }
    
    /**
     * @param string $name
     * @param int $val
     */
    private function _namedDataInc( $name, $val )
    {
        /* @var $data MergeData */
        $data = $this->_namedBlockDatas[$name];
        $data->openCount += (int)$val;
    }
    
    /**
     * 
     */
    private function _finalize()
    {
        foreach ($this->_namedBlockDatas as $data) {
            $this->_finalizeDataSupers($data);
        }
    }
    
    /**
     * @param MergeData $data
     */
    private function _finalizeDataSupers( MergeData $data )
    {
        $topBlock = $data->block;
        
        /* @var $s MergeData */
        $s = $data;
        while ($s->super) {
            unset($s->parent->children[$s->index]);
            $s = $s->super;
        }
        $s->parent->children[$s->index] = $topBlock;
    }
}
