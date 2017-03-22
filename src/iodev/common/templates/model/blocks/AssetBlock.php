<?php

namespace iodev\commons\templates\model\blocks;

use iodev\commons\templates\model\Asset;
use iodev\commons\templates\model\Block;
use iodev\commons\templates\model\BlockType;

/**
 * @author Sergey Sedyshev
 */
class AssetBlock extends Block
{
    /**
     * @param Asset $asset
     */
    public function __construct( Asset $asset )
    {
        $this->asset = $asset;
    }
    
    /** @var Asset */
    public $asset;
    
    /**
     * @return string
     */
    public function type()
    {
        return BlockType::ASSET;
    }
}
