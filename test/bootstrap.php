<?php
date_default_timezone_set('Europe/Helsinki');
$sources = glob(__DIR__ . '/../src/*/*.php');
foreach ($sources as $phpFile)
{
    include_once $phpFile;
}

function D()
{
    $params = func_get_args();
    if ($params)
    {
        ob_end_flush();
        foreach ($params as $param)
        {
            var_dump($param);
        }
        echo "---------------\n";
        ob_start();
    }
}

