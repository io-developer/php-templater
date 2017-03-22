<?php

namespace iodev\commons\templates\model;

use iodev\commons\templates\model\Block;

/**
 * @author Sergey Sedyshev
 */
interface ICloner
{
    /**
     * @param Block $block
     * @return Block
     */
    function cloneBlock( Block $block );
}
