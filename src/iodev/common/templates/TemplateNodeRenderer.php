<?php

namespace iodev\commons\templates;

use iodev\commons\templates\model\IBuffer;
use iodev\commons\templates\model\NodeBuilder;
use iodev\commons\templates\model\renderers\BaseNodeRenderer;

/**
 * @author Sergey Sedyshev
 */
class TemplateNodeRenderer extends BaseNodeRenderer
{
    public function __construct( IBuffer $buffer )
    {
        parent::__construct($buffer);
        
        $this->_filter = new TemplateFilter();
    }
    
    
    /** @var TemplateFilter */
    private $_filter;
    
    
    /**
     * @param string $file
     * @param string $path
     * @param array $params
     * @param NodeBuilder $builder
     */
    protected function renderNodeFile( $file, $path, $params, $builder )
    {
        $t = new Template($file, $path, $params, $builder, $this->_filter);
    }
}
