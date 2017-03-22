<?php

namespace iodev\commons\templates\model;

use iodev\commons\templates\model\blocks\NodeBlock;

/**
 * @author Sergey Sedyshev
 */
class Node
{
    /**
     * @param NodeBlock $rootBlock
     */
    public function __construct( $rootBlock=null )
    {
        $this->rootBlock = $rootBlock;
    }
    
    
    /** @var string */
    public $path;
    
    /** @var string */
    public $extendPath;
    
    /** @var NodeBlock */
    public $rootBlock;
}
