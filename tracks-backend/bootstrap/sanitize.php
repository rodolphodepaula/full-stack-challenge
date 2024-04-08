<?php

/**
 * Funcoes Helpers para Sanitizar Dados.
 */

 if (! function_exists('remove_accents')) {
    function remove_accents($string)
    {
        return iconv('UTF-8', 'US-ASCII//TRANSLIT//IGNORE', $string);
    }
}