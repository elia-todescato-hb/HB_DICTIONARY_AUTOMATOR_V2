<?php

function warning($string = "")
{
    $warning = 'yellow';
    # code...
    echo "<pre style='color: {$warning}'><p>{$string}</p></pre>";
}

function error($string = "")
{
    $warning = 'red';
    # code...
    echo "<pre style='color: {$warning}'><p>{$string}</p></pre>";
}

function info($string = "")
{
    $warning = 'blue';
    # code...
    echo "<pre style='color: {$warning}'><p>{$string}</p></pre>";
}
