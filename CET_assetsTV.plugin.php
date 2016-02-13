<?php
/*
Part of Content Editor Tolls. Best with CET_TinyMCE or CET_CKEditor
Adds assetsTV input type for TVs, where you can upload files and insert them to Rich Text Editor. Supports TinyMCE and CKEditor. 
You can output files with assetsTV snippet.
Author Denis Dyranov (Dyranov.ru)
Version: 0.8
*/
$corePath = $modx->getOption('core_path',null,MODX_CORE_PATH).'components/assetstv/';
$assetsUrl = $modx->getOption('assets_url',null,MODX_ASSETS_URL).'components/assetstv/';

//$modx->lexicon->load('assetstv:default');

switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($corePath.'elements/tv/input/');
        break;
    case 'OnTVInputPropertiesList':
        $modx->event->output($corePath.'elements/tv/input/options/');
        break;
}