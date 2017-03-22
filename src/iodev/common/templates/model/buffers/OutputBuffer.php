<?php

namespace iodev\commons\templates\model\buffers;

use iodev\commons\templates\model\IBuffer;

/**
 * @author Sergey Sedyshev
 */
class OutputBuffer implements IBuffer
{
    /**
     * 
     */
    public function start()
    {
        ob_start();
    }
    
    /**
     * @return string
     */
    public function end()
    {
        return ob_get_clean();
    }
    
    /**
     * @return string
     */
    public function snapshot()
    {
        $s = $this->end();
        $this->start();
        return $s;
    }
}
