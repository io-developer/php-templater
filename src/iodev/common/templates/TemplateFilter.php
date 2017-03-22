<?php

namespace iodev\commons\templates;

/**
 * @author Sergey Sedyshev
 */
class TemplateFilter
{
    /**
     * @param string $text
     * @param bool $removeNl
     * @return string
     */
    public function attr( $text, $removeNl=false )
    {
        return str_replace(["\r\n", "\n", "\r"], $removeNl ? "" : "&#10;", self::text($text));
    }
    
    /**
     * @param string $text
     * @param bool $nl2br
     * @param int $flags
     * @return string
     */
    public function text( $text, $nl2br=false, $flags=null, $encoding="utf-8" )
    {
        $flags = $flags ? $flags : ENT_QUOTES | ENT_DISALLOWED;
        $t = htmlspecialchars($text, $flags, $encoding);
        if ($nl2br) {
            $t = nl2br($t);
        }
        return $t;
    }
    
    /**
     * @param string $text
     * @return string
     */
    public function escape( $text )
    {
        return addslashes($text);
    }
    
    /**
     * @param string $text
     * @return string
     */
    public function trim( $text )
    {
        return trim($text);
    }
    
    /**
     * @param int $num
     * @param string $separator
     * @param int $digits
     * @return string
     */
    public function groupDigits( $num, $separator=" ", $digits=3 )
    {
        $p = '(\d{'.(int)$digits.'})';
        $re = "/{$p}?{$p}?{$p}?{$p}?{$p}$/ui";
        $s = preg_replace($re, ' $1 $2 $3 $4 $5 ', ''.(int)$num);
        $s = preg_replace('/^\s+|\s+$/ui', '', $s);
        return preg_replace('/\s+/ui', $separator, $s);
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
        return $this->groupDigits($num) . " " . $this->ruLabelForInt($num, $labelA, $labelB, $labelC);
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
        $n = (int)$num;
		$d = $n % 100;
		if ($d > 4 && $d < 20) {
			return $labelC;
		}
		$d = $n % 10;
		if ($d == 1) {
			return $labelA;
		}
		if ($d > 0 && $d < 5) {
			return $labelB;
		}
		return $labelC;
    }
    
    /**
     * @param string $val
     * @param array $options
     * @return string
     */
    public function selectOptions( $val, $options )
    {
        $s = "";
        foreach ($options as $key => $text) {
            $selected = ($key == $val) ? "selected" : "";
            $s .= "<option value='".self::attr($key)."' {$selected}>"
                . self::text($text)
                . "</option>";
        }
        return $s;
    }
}
