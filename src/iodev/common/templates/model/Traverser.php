<?php

namespace iodev\commons\templates\model;

use iodev\commons\templates\model\blocks\AssetBlock;
use iodev\commons\templates\model\blocks\AssetContainerBlock;
use iodev\commons\templates\model\blocks\NodeBlock;
use iodev\commons\templates\model\blocks\RenderBlock;
use iodev\commons\templates\model\blocks\RenderIncludedBlock;
use iodev\commons\templates\model\BlockType;

/**
 * @author Sergey Sedyshev
 */
class Traverser
{
    public function __construct()
    {
    }


    /**
     * @param NodeBlock $root
     * @param string $type
     * @return Block[]
     */
    public function findBlocksByType( NodeBlock $root, $type )
    {
        $blocks = [];
        $this->_findBlocksByType($root, $type, $blocks);
        return $blocks;
    }
    
    
    /**
     * @param NodeBlock $root
     * @return RenderBlock[]
     */
    public function findRenderBlocks( NodeBlock $root )
    {
        return $this->findBlocksByType($root, BlockType::RENDER);
    }
    
    /**
     * @param NodeBlock $root
     * @return RenderBlock[]
     */
    public function findIncompleteRenderBlocks( NodeBlock $root )
    {
        $outBlocks = [];
        $blocks = $this->findRenderBlocks($root);
        foreach ($blocks as $block) {
            if (!$block->rendered) {
                $outBlocks[] = $block;
            }
        }
        return $outBlocks;
    }
    
    
    /**
     * @param NodeBlock $root
     * @return RenderIncludedBlock[]
     */
    public function findRenderInclBlocks( NodeBlock $root )
    {
        return $this->findBlocksByType($root, BlockType::RENDER_INCLUDED);
    }
    
    /**
     * @param NodeBlock $root
     * @return RenderIncludedBlock[]
     */
    public function findIncompleteRenderInclBlocks( NodeBlock $root )
    {
        $outBlocks = [];
        $blocks = $this->findRenderInclBlocks($root);
        foreach ($blocks as $block) {
            if (!$block->rendered) {
                $outBlocks[] = $block;
            }
        }
        return $outBlocks;
    }
    
    /**
     * @param NodeBlock $root
     * @return Asset[]
     */
    public function findAssets( NodeBlock $root )
    {
        $assets = [];
        $blocks = $this->findAssetBlocks($root);
        foreach ($blocks as $block) {
            $assets[] = $block->asset;
        }
        return $assets;
    }


    /**
     * @param NodeBlock $root
     * @return AssetBlock[]
     */
    public function findAssetBlocks( NodeBlock $root )
    {
        return $this->findBlocksByType($root, BlockType::ASSET);
    }

    /**
     * @param NodeBlock $root
     * @return AssetContainerBlock[]
     */
    public function findAssetContainerBlocks( NodeBlock $root )
    {
        return $this->findBlocksByType($root, BlockType::ASSET_CONTAINER);
    }
    
    
    /**
     * @param NodeBlock $root
     * @param string $type
     * @param Block[] $outList
     */
    private function _findBlocksByType( $root, $type, &$outList )
    {
        if (!$root) {
            return;
        }
        foreach ($root->children as $child) {
            $t = $child->type();
            if ($t == $type) {
                $outList[] = $child;
            }
            
            if ($t == BlockType::NODE) {
                $this->_findBlocksByType($child, $type, $outList);
            } elseif ($t == BlockType::RENDER_INCLUDED) {
                $this->_findBlocksByType($child->renderedRoot, $type, $outList);
            }
        }
    }
}
