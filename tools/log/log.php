<?php

function warning($string = "")
{
    $warning = 'yellow';
    echo "<pre style='color: {$warning}'><p>{$string}</p></pre>";
}

function error($string = "")
{
    $warning = 'red';
    echo "<pre style='color: {$warning}'><p>{$string}</p></pre>";
}

function info($string = "")
{
    $warning = 'blue';
    echo "<pre style='color: {$warning}'><p>{$string}</p></pre>";
}
