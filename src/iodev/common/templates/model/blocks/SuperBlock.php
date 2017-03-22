<?php

namespace iodev\commons\templates\model\blocks;

use iodev\commons\templates\model\Block;
use iodev\commons\templates\model\BlockType;

/**
 * @author Sergey Sedyshev
 */
class SuperBlock extends Block
{
    /**
     * @return string
     */
    public function type()
    {
        return BlockType::SUPER;
    }
}
