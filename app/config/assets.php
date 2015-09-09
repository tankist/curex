<?php

$assets = new \Phalcon\Assets\Manager();

$assets
    ->collection('layoutCss')
        ->setPrefix('components/')
        ->addCss('bootstrap/dist/css/bootstrap.min.css')
        ->addCss('flat-ui/dist/css/flat-ui.min.css')
        ->addCss('select2/dist/css/select2.min.css')
;

$assets
    ->collection('layoutJs')
        ->setPrefix('components/')
        ->addJs('jquery/dist/jquery.min.js')
        ->addJs('bootstrap/dist/js/bootstrap.min.js')
        ->addJs('select2/dist/js/select2.full.min.js')
        ->addJs('flat-ui/dist/js/flat-ui.min.js')
        ->addJs('../js/app.js')
;

return $assets;