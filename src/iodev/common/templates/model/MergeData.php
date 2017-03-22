<?php

namespace iodev\commons\templates\model;

use iodev\commons\templates\model\blocks\NodeBlock;

/**
 * @author Sergey Sedyshev
 */
class MergeData
{
    /**
     * @param Block $block
     * @param NodeBlock $parent
     * @param string $index
     * @param MergeData $super
     */
    public function __construct( $block=null, $parent=null, $index=null, $super=null )
    {
        $this->block = $block;
        $this->parent = $parent;
        $this->index = $index;
        $this->super = $super;
    }
    
    /** @var MergeData */
    public $super;
    
    /** @var Block */
    public $block;
    
    /** @var NodeBlock */
    public $parent;
    
    /** @var string */
    public $index;
    
    /** @var int */
    public $openCount = 0;
    
    /** @var bool */
    public $usedAsSuper = false;
}
