<?php

namespace iodev\commons\templates\model;

/**
 * @author Sergey Sedyshev
 */
interface INodeRenderer
{
    /**
     * @param string $path
     * @param array $params
     * @return Node
     */
    function renderNode( $path, $params );
}
