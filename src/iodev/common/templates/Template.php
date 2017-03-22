<?php

namespace iodev\commons\templates;

use iodev\commons\templates\model\Asset;
use iodev\commons\templates\model\AssetType;
use iodev\commons\templates\model\NodeBuilder;

/**
 * @author Sergey Sedyshev
 */
class Template
{
    /**
     * @param string $file
     * @param string $path
     * @param array $params
     * @param NodeBuilder $builder
     * @param TemplateFilter $filter
     */
    public function __construct( $file, $path, $params, NodeBuilder $builder, TemplateFilter $filter )
    {
        $this->_file = $file;
        $this->_path = $path;
        $this->_params = $params;
        $this->_builder = $builder;
        $this->_filter = $filter;
        $this->_render();
    }


    /** @var string */
    private $_file;
    
    /** @var string */
    private $_path;
    
    /** @var array */
    private $_params;
    
    /** @var NodeBuilder */
    private $_builder;
    
    /** @var TemplateFilter */
    private $_filter;
    
    /** @var string */
    private $_lastElemId = "";
    
    
    /**
     * @return string
     */
    public function dir()
    {
        return pathinfo($this->_path, PATHINFO_DIRNAME);
    }
    
    /**
     * @return string
     */
    public function path()
    {
        return $this->_path;
    }
    
    /**
     * @param string $path
     * @return type
     */
    public function relpath( $path )
    {
        return $this->_resolvePathRelativeTo($path, $this->_path);
    }
    
    /**
     * @param string $path
     * @return type
     */
    public function webpath( $path )
    {
        return preg_replace('%^'.$_SERVER["DOCUMENT_ROOT"].'%i', '', $path);
    }
    
    /**
     * @param string $key
     * @return mixed
     */
    public function param( $key )
    {
        return $this->_params[$key];
    }
    
    /**
     * @return TemplateFilter
     */
    public function filter()
    {
        return $this->_filter;
    }


    /**
     * @param string $path
     * @param array $params
     * @return string
     */
    public function render( $path, $params=null )
    {
        $this->_builder->render($this->relpath($path), $params ? $params : []);
        return "";
    }
    
    /**
     * @param string $path
     * @return string
     */
    public function renderIncluded( $path )
    {
        $this->_builder->renderIncluded($this->relpath($path), $this->_params);
        return "";
    }
    
    /**
     * @param string $path
     * @return string
     */
    public function extend( $path )
    {
        $this->_builder->extend($this->relpath($path));
        return "";
    }
    
    /**
     * @param string $name
     * @return string
     */
    public function block( $name )
    {
        $this->_builder->block($name);
        return "";
    }
    
    /**
     * @return string
     */
    public function endblock()
    {
        $this->_builder->endblock();
        return "";
    }
    
    /**
     * @return string
     */
    public function superblock()
    {
        $this->_builder->superblock();
        return "";
    }
    
    /**
     * @return string
     */
    public function requireds()
    {
        $this->_builder->requiredAssets();
        return "";
    }
    
    /**
     * @return string
     */
    public function requiredScripts()
    {
        $this->_builder->requiredAssets(AssetType::SCRIPT);
        return "";
    }
    
    /**
     * @return string
     */
    public function requiredStyles()
    {
        $this->_builder->requiredAssets(AssetType::STYLE);
        return "";
    }
    
    /**
     * @param string $path
     * @param array $options
     * @return string
     */
    public function requiredsByType( $type )
    {
        $this->_builder->requiredAssets($type);
        return "";
    }
    
    
    /**
     * @param string $path
     * @param array $options
     * @return string
     */
    public function requireScript( $path, $options=null )
    {
        return $this->requireAsset(AssetType::SCRIPT, $this->relpath($path), $options);
    }
    
    /**
     * @param string $path
     * @param array $options
     * @return string
     */
    public function requireStyle( $path, $options=null )
    {
        return $this->requireAsset(AssetType::STYLE, $this->relpath($path), $options);
    }
    
    /**
     * @param string $path
     * @param array $options
     * @return string
     */
    public function requireAsset( $type, $path, $options=null )
    {
        $this->_builder->requireAsset(new Asset($type, $this->relpath($path), $options));
        return "";
    }



    /**
     * @param int $len
     * @return string
     */
    public function nextElemId( $len=10 )
    {
        $id = "id_";
        while ($len-- > 0) {
            $id .= rand(0, 9);
        }
        $this->_lastElemId = $id;
        return $id;
    }
    
    /**
     * @return string
     */
    public function lastElemId()
    {
        return $this->_lastElemId;
    }
    
    
    /**
     * @param string $text
     * @param bool $removeNl
     * @return string
     */
    public function attr( $text, $removeNl=false )
    {
        return $this->_filter->attr($text, $removeNl);
    }
    
    /**
     * @param string $text
     * @param bool $nl2br
     * @param int $flags
     * @return string
     */
    public function text( $text, $nl2br=false, $flags=null, $encoding="utf-8" )
    {
        return $this->_filter->text($text, $nl2br, $flags, $encoding);
    }
    
    /**
     * @param string $text
     * @return string
     */
    public function escape( $text )
    {
        return $this->_filter->escape($text);
    }
    
    /**
     * @param string $text
     * @return string
     */
    public function trim( $text )
    {
        return $this->_filter->trim($text);
    }
    
    /**
     * @param int $num
     * @param string $separator
     * @param int $digits
     * @return string
     */
    public function groupDigits( $num, $separator=" ", $digits=3 )
    {
        return $this->_filter->groupDigits($num, $separator, $digits);
    }
    
    /**
     * @param int $num
     * @param string $labelA
     * @param string $labelB
     * @param string $labelC
     * @return string
     */
    public function groupDigitsWithRuLabel( $num, $labelA="штука", $labelB="штуки", $labelC="штук" )
    {
        return $this->_filter->groupDigitsWithRuLabel($num, $labelA, $labelB, $labelC);
    }
    
    /**
     * @param int $num
     * @param string $labelA
     * @param string $labelB
     * @param string $labelC
     * @return string
     */
    public static function ruLabelForInt( $num, $labelA="штука", $labelB="штуки", $labelC="штук" )
    {
        return $this->_filter->ruLabelForInt($num, $labelA, $labelB, $labelC);
    }
    
    /**
     * @param string $val
     * @param array $options
     * @return string
     */
    public function selectOptions( $val, $options )
    {
        return $this->_filter->selectOptions($val, $options);
    }
    
    /**
     * 
     */
    private function _render()
    {
        $t = $this;
        $p = $params = $this->_params;
        
        $this->_builder->start();
        include $this->_file;
        $this->_builder->end();
    }
    
    /**
     * @param string $path
     * @param string $current
     * @return string
     */
    private function _resolvePathRelativeTo( $path, $current )
    {
        $i = mb_strpos($path, "://");
        if (mb_strpos($path, "//") === 0 || ($i !== false && $i <= 5) ) {
            return $path;
        }
        
        $p = trim($path);
        if ($p[0] === '/') {
            $root = $_SERVER["DOCUMENT_ROOT"];
            $p = (mb_strpos($p, $root) !== false) ? $p : $root . $p;
        } else {
            $p = pathinfo($current, PATHINFO_DIRNAME) . "/" . $path;
        }
        
        return $this->_resolvePath($p);
    }
    
    /**
     * @param string $path
     * @return string
     */
    private function _resolvePath( $path )
    {
        $parts = explode("/", $path);
        $out = [];
        $l = 0;
        foreach ($parts as $part) {
            if ($part == ".") {
                continue;
            }
            if ($part == ".." && $l > 0 && $out[$l - 1] != "..") {
                array_pop($out);
                $l--;
            } else {
                $out[] = $part;
                $l++;
            }
        }
        return implode("/", $out);
    }
}
