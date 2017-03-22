<?php

namespace iodev\commons\templates\model\renderers;

use iodev\commons\templates\model\IBuffer;
use iodev\commons\templates\model\INodeRenderer;
use iodev\commons\templates\model\Node;
use iodev\commons\templates\model\NodeBuilder;

/**
 * @author Sergey Sedyshev
 */
abstract class BaseNodeRenderer implements INodeRenderer
{
    /**
     * @param IBuffer $buffer
     */
    public function __construct( IBuffer $buffer )
    {
        $this->buffer = $buffer;
    }
    
    
    /** @var IBuffer */
    protected $buffer;
    
    
    /**
     * @param string $file
     * @param string $path
     * @param array $params
     * @param NodeBuilder $builder
     */
    abstract protected function renderNodeFile( $file, $path, $params, $builder );
    
    /**
     * @param string $path
     * @param array $params
     * @return Node
     */
    public function renderNode( $path, $params )
    {
        $builder = new NodeBuilder($this->buffer);
        $this->renderNodeFile($path, $path, $params, $builder);
        return $builder->toNode();
    }
}
