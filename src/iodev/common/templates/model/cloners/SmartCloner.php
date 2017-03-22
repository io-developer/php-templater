<?php

namespace iodev\commons\templates\model\cloners;

use iodev\commons\templates\model\Block;
use iodev\commons\templates\model\blocks\NodeBlock;
use iodev\commons\templates\model\blocks\NodeBlock;
use iodev\commons\templates\model\blocks\RenderIncludedBlock;
use iodev\commons\templates\model\BlockType;
use iodev\commons\templates\model\ICloner;

/**
 * @author Sergey Sedyshev
 */
class SmartCloner implements ICloner
{
    /**
     * @param Block $block
     * @return Block
     */
    public function cloneBlock( Block $block )
    {
        $t = $block->type();
        if ($t == BlockType::NODE) {
            return $this->cloneNodeBlock($block);
        }
        if ($t == BlockType::RENDER_INCLUDED) {
            return $this->cloneRenderIncludedBlock($block);
        }
        return $block;
    }
    
    /**
     * @param NodeBlock $block
     * @return NodeBlock
     */
    public function cloneNodeBlock( NodeBlock $block )
    {
        $b = clone $block;
        if ($block->children) {
            $b->children = [];
            foreach ($block->children as $child) {
                $b->children[] = $this->cloneBlock($child);
            }
        }
        return $b;
    }
    
    /**
     * @param RenderIncludedBlock $block
     * @return RenderIncludedBlock
     */
    public function cloneRenderIncludedBlock( RenderIncludedBlock $block )
    {
        $b = clone $block;
        if ($block->renderedRoot) {
            $b->renderedRoot = $this->cloneNodeBlock($block->renderedRoot);
        }
        return $b;
    }
}
