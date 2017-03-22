<?php

namespace iodev\commons\templates\model;

/**
 * @author Sergey Sedyshev
 */
abstract class Block
{
    /**
     * @return string
     */
    abstract public function type();
    
    /**
     * @return string
     */
    public function toString()
    {
        return "";
    }
}
