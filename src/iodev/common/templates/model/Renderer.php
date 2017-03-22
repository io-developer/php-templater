<?php

namespace iodev\commons\templates\model;

use iodev\commons\templates\model\blocks\AssetContainerBlock;
use iodev\commons\templates\model\blocks\NodeBlock;
use iodev\commons\templates\model\blocks\RenderIncludedBlock;
use iodev\commons\templates\model\renderers\AssetRenderer;

/**
 * @author Sergey Sedyshev
 */
class Renderer
{
    public function __construct( INodeRenderer $nodeRenderer, Merger $merger )
    {
        $this->_nodeRenderer = $nodeRenderer;
        $this->_merger = $merger;
        $this->_traverser = new Traverser();
        $this->_assetRenderer = new AssetRenderer();
    }
    
    
    /** @var INodeRenderer */
    private $_nodeRenderer;
    
    /** @var Merger */
    private $_merger;
    
    /** @var Traverser */
    private $_traverser;
    
    /** @var AssetRenderer */
    private $_assetRenderer;
    
    
    /**
     * @param string $path
     * @param array $params
     * @return RenderOutput
     */
    public function renderFinalOutput( $path, $params=null )
    { 
        $data = $this->renderData($path, $params);
        
        $root = $data->root;
        $this->_finalize($root, $data->requiredAssets);
        
        $output = new RenderOutput();
        $output->content = $root->toString();
        
        //traceVarDumpFor("final root: ", $root);
        
        return $output;
    }
    
    /**
     * @param string $path
     * @param array $params
     * @return RenderOutput
     */
    public function renderOutput( $path, $params=null )
    {
        $data = $this->renderData($path, $params);     
        $output = new RenderOutput();
        $output->content = $data->root->toString();
        $output->requiredAssets = $data->requiredAssets;
        return $output;
    }
    
    
    /**
     * @param string $path
     * @param array $params
     * @return RenderData
     */
    public function renderData( $path, $params=null )
    {
        $node = $this->_nodeRenderer->renderNode($path, $params);
    //  $requiredAssets = $this->_traverser->findAssets($node->rootBlock);
        
        $blocks = [ $node->rootBlock ];
        while ($node->extendPath) {
            $node = $this->_nodeRenderer->renderNode($node->extendPath, $params);
            $blocks[] = $node->rootBlock;
        }
        
        $data = new RenderData();
        $data->root = $this->_merger->mergeBlocks(array_reverse($blocks));
        $data->requiredAssets = $this->_traverser->findAssets($data->root);
        
        $this->_renderIncludesIn($data);
        $data->requiredAssets = array_merge(
            $data->requiredAssets
            , $this->_traverser->findAssets($data->root)
        );
        
        $this->_renderIsolatedsIn($data);
        
        $data->requiredAssets = $this->_uniqAssets($data->requiredAssets);
        
        return $data;
    }
    
    /**
     * @param RenderData $data
     */
    private function _renderIncludesIn( RenderData $data )
    {
        while (true) {
            $blocks = $this->_traverser->findIncompleteRenderInclBlocks($data->root);
            if (count($blocks) > 0) {
                $this->_renderIncludesPass($blocks);
                $this->_merger->mergeBlock($data->root);
            } else {
                return;
            }
        }
    }
    
    /**
     * @param RenderIncludedBlock[] $blocks
     */
    private function _renderIncludesPass( $blocks )
    {
        foreach ($blocks as $block) {
            $node = $this->_nodeRenderer->renderNode($block->path, $block->params);
            $block->renderedRoot = $node->rootBlock;
            $block->rendered = true;
        }
    }
    
    /**
     * @param RenderData $data
     */
    private function _renderIsolatedsIn( RenderData $data )
    {
        $blocks = $this->_traverser->findIncompleteRenderBlocks($data->root);
        foreach ($blocks as $block) {
            $output = $this->renderOutput($block->path, $block->params);
            $block->rendered = true;
            $block->renderedContent = $output->content;
            $block->requiredAssets = $output->requiredAssets;
            $data->requiredAssets = array_merge(
                $data->requiredAssets
                , $output->requiredAssets
            );
        }
    }
    
    /**
     * @param NodeBlock $root
     * @param Asset[] $requiredAssets
     */
    private function _finalize( NodeBlock $root, $requiredAssets )
    {
        $this->_finalizeAssets($root, $requiredAssets);
    }
    
    /**
     * @param NodeBlock $root
     * @param Asset[] $requiredAssets
     */
    private function _finalizeAssets( NodeBlock $root, $requiredAssets )
    {
        $containers = $this->_typedAssetContainerBlocks($root);        
        $uniqAssets = $this->_uniqAssetsByPath($requiredAssets);
        
        foreach ($uniqAssets as $asset) {
            $t = $asset->type;
            /* @var $container AssetContainerBlock */
            $container = $containers[$t] ? $containers[$t] : $containers["*"];
            if ($container) {
                $container->assets[] = $asset;
            }
        }
        
        foreach ($containers as $container) {
            $contents = [];
            foreach ($container->assets as $asset) {
                $contents[] = $this->_assetRenderer->renderAsset($asset);
            }
            $container->renderedContent = implode("\n", $contents);
            $container->rendered = true;
        }
    }
    
    /**
     * 
     * @param NodeBlock $root
     * @return AssetContainerBlock[]
     */
    private function _typedAssetContainerBlocks( NodeBlock $root )
    {
        $containers = $this->_traverser->findAssetContainerBlocks($root);
        
        $typedContainers = [];
        foreach ($containers as $container) {
            $t = $container->assetType ? $container->assetType : "*";
            $typedContainers[$t] = $typedContainers[$t]
                ? $typedContainers[$t]
                : $container;
        }
        
        return $typedContainers;
    }
    
    /**
     * @param Asset[] $assets
     * @return Asset[]
     */
    private function _uniqAssets( $assets )
    {
        return array_unique($assets, SORT_REGULAR);
    }
    
    /**
     * @param Asset[] $assets
     * @return Asset[]
     */
    private function _uniqAssetsByPath( $assets )
    {
        $uniqs = [];
        foreach ($assets as $asset) {
            $uniqs[$asset->path] = $asset;
        }
        return $uniqs;
    }
}
