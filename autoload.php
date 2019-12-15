<?php

spl_autoload_register(function ($class_name) {
    require_once __DIR__.'/'.$class_name .'.php';
});