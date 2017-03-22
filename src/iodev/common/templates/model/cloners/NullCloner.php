<?php

namespace iodev\commons\templates\model\cloners;

use iodev\commons\templates\model\Block;
use iodev\commons\templates\model\ICloner;

/**
 * @author Sergey Sedyshev
 */
class NullCloner implements ICloner
{
    /**
     * @param Block $block
     * @return Block
     */
    public function cloneBlock( Block $block )
    {
        return $block;
    }
}
