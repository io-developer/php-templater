<?php

namespace iodev\commons\templates\model\renderers;

use iodev\commons\templates\model\Asset;
use iodev\commons\templates\model\AssetType;
use progorod\helpers\Path;

/**
 * @author Sergey Sedyshev
 */
class AssetRenderer
{
    public function __construct()
    {
    }
    
    /**
     * @param Asset $asset
     * @return string
     */
    public function renderAsset( Asset $asset )
    {
        $type = $asset->type;
        if ($type == AssetType::SCRIPT) {
            return $this->renderScriptAsset($asset);
        }
        if ($type == AssetType::STYLE) {
            return $this->renderStyleAsset($asset);
        }
        return "";
    }
    
    /**
     * @param Asset $asset
     * @return string
     */
    public function renderScriptAsset( Asset $asset )
    {
        if (!is_file($asset->path)) {
            return "";
        }
        return ''
            . '<script'
            .   ' type="text/javascript"'
            .   ' src="' . htmlspecialchars(Path::toRoot($asset->path), ENT_QUOTES) . '"'
            . '></script>';
    }
    
    /**
     * @param Asset $asset
     * @return string
     */
    public function renderStyleAsset( Asset $asset )
    {
        if (!is_file($asset->path)) {
            return "";
        }
        return ''
            . '<link'
            .   ' type="text/css"'
            .   ' rel="stylesheet"'
            .   ' media="all"'
            .   ' href="' . htmlspecialchars(Path::toRoot($asset->path), ENT_QUOTES) . '"'
            . '/>';
    }
}
