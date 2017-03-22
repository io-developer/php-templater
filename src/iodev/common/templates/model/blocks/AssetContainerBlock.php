<?php

namespace iodev\commons\templates\model\blocks;

use iodev\commons\templates\model\Asset;
use iodev\commons\templates\model\Block;
use iodev\commons\templates\model\BlockType;

/**
 * @author Sergey Sedyshev
 */
class AssetContainerBlock extends Block
{
    /**
     * @param string $assetType
     */
    public function __construct( $assetType )
    {
        $this->assetType = $assetType;
    }
    
    
    /** @var string */
    public $assetType;
    
    /** @var Asset[] */
    public $assets = [];
    
    /** @var bool */
    public $rendered = false;
    
    /** @var string */
    public $renderedContent = "";


    /**
     * @return string
     */
    public function type()
    {
        return BlockType::ASSET_CONTAINER;
    }
    
    /**
     * @return string
     */
    public function toString()
    {
        return $this->rendered ? $this->renderedContent : "";
    }
}
