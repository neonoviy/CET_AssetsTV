<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';
 
$corePath = $modx->getOption('core_path').'components/assetstv/';
 
//if ($_REQUEST['action'] == 'browser/file/list') {
//    $version = $modx->getVersionData();
//    if (version_compare($version['full_version'],'2.1.1-pl') >= 0) {
//        if ($modx->user->hasSessionContext($modx->context->get('key'))) {
//            $_SERVER['HTTP_MODAUTH'] = $_SESSION["modx.{$modx->context->get('key')}.user.token"];
//        } else {
//            $_SESSION["modx.{$modx->context->get('key')}.user.token"] = 0;
//            $_SERVER['HTTP_MODAUTH'] = 0;
//        }
//    } else {
//        $_SERVER['HTTP_MODAUTH'] = $modx->site_id;
//    }
//    $_REQUEST['HTTP_MODAUTH'] = $_SERVER['HTTP_MODAUTH'];
//}    
// Load the upload processor class for extension
require_once $modx->getOption('core_path').'model/modx/modprocessor.class.php';
require_once $modx->getOption('core_path').'model/modx/processors/browser/file/upload.class.php';
require_once $modx->getOption('core_path').'model/modx/processors/browser/file/remove.class.php';


/* handle request */
$path = $corePath.'processors/';
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));

