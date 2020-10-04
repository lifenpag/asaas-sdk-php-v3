<?php declare(strict_types = 1);

if (! function_exists('dd')) {

    function dd($value)
    {
        var_dump($value);
        die;
    }
}

if (! function_exists('vdl')) {

    function vdl($value)
    {
        var_dump($value);
        echo PHP_EOL;
    }
}
