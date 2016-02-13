<?php
/**
 * Upload files to a directory
 *
 * @param string $path The target directory
 *
 * @package CET_AssetsTV
 * @subpackage processors.browser.file
 */
class assetstvFileUploadProcessor extends modProcessor {
    /** @var modMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('file_upload');
    }

    public function getLanguageTopics() {
        return array('file');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'source' => 1,
            'path' => false,
        ));
        if (!$this->getProperty('path')) return $this->modx->lexicon('file_folder_err_ns');
        return true;
    }

    public function process() {
        if (!$this->getSource()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();
        if (!$this->source->checkPolicy('create')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        $this->ensureSavePathExists($_POST['path']);
        
        $success = $this->source->uploadObjectsToContainer($_POST['path'],$_FILES);

				/* Check for upload errors
				 * Remove 'directory already exists' error
				 */
				$errors = array();
				if (empty($success)) {
				    $msg = '';
				    $errors = $this->source->getErrors();
				    if(isset($errors['name'])){ unset($errors['name']); };
				};
				if(count($errors)>0){
				    foreach ($errors as $k => $msg) {
				        $this->modx->error->addField($k,$msg);
				    }
				    return $this->failure($msg);
				};

        return $this->success();
    }

    // Ensure save path exists (and create it if not)
    //----------------------------------------------------------------------------
    private function ensureSavePathExists($path){
    		$this->source->createContainer($path,'');
    	}//
    

    /**
     * Get the active Source
     * @return modMediaSource|boolean
     */
    public function getSource() {
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = $this->modx->getObject('modMediaSource', $_POST['sourceID']);
        if (empty($this->source) || !$this->source->getWorkingContext()) {
            return false;
        }
        return $this->source;
    }
}
return 'assetstvFileUploadProcessor';