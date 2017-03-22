<?php

namespace iodev\commons\templates\model;

use iodev\commons\templates\model\blocks\NodeBlock;

/**
 * @author Sergey Sedyshev
 */
class RenderData
{
    /** @var NodeBlock */
    public $root;
    
    /** @var Asset[] */
    public $requiredAssets = [];
}
