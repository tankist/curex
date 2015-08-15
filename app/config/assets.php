<?php

$assets = new \Phalcon\Assets\Manager();

$assets
    ->collection('layoutCss')
        ->setPrefix('components/')
        ->addCss('bootstrap/dist/css/bootstrap.min.css')
;

$assets
    ->collection('layoutJs')
        ->setPrefix('components/')
        ->addJs('jquery/dist/jquery.min.js')
        ->addJs('bootstrap/dist/js/bootstrap.min.js')
;

return $assets;