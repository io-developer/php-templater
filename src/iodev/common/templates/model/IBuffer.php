<?php

namespace iodev\commons\templates\model;

/**
 * @author Sergey Sedyshev
 */
interface IBuffer
{
    /**
     * @return string
     */
    function snapshot();
    
    /**
     * 
     */
    function start();
    
    /**
     * @return string
     */
    function end();
}
