<?php

main();

function main(){

    init_auto_load();

    if(isset($_GET['page'])){
        navigate($_GET['page']);
    }
    else{
        safe_navigate('test');
    }
}

function safe_navigate($page){
    try{
        navigate($page);
    }
    catch(Exception $e){
        echo $e->getMessage();
    }
}

function navigate($page){
    $className = $page.'Controller';
    $controller = new $className();
    $controller->handle();
}

function init_auto_load(){
    spl_autoload_register('auto_loader');
}

function auto_loader($class){
    load('controller', $class);
    load('grabber', $class);
    load('quote', $class);
    load('view', $class);
    load('db', $class);
}

function load($subject, $cl){
    $file = $subject . '/' . $cl . '.php';

    if(file_exists($file) && is_readable($file)){
        include_once $file;
    }
}