<?php
/**
 * Removes a file.
 *
 * @param string $file The name of the file.
 * @param boolean $prependPath If true, will prepend the rb_base_dir to the file
 * name.
 *
 * @package modx
 * @subpackage processors.browser.file
 */
class assetstvRemoveProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('file_remove');
    }
    public function getLanguageTopics() {
        return array('file');
    }

    public function process() {
        $file = $this->getProperty('file');
        if (empty($file)) {
            return $this->modx->error->failure($this->modx->lexicon('file_err_ns'));
        }

        $loaded = $this->getSource();
        if (!($this->source instanceof modMediaSource)) {
            return $loaded;
        }
        if (!$this->source->checkPolicy('remove')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $success = $this->source->removeObject($file);

        if (empty($success)) {
            $errors = $this->source->getErrors();
            $msg = implode("\n", $errors);

            return $this->failure($msg);
        }

        return $this->success();
    }

    /**
     * @return boolean|string
     */
    public function getSource() {

        /** @var modMediaSource $source */
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = $this->modx->getObject('modMediaSource', $_POST['sourceID']);
        if (!$this->source->getWorkingContext()) {
            return $this->modx->lexicon('permission_denied');
        }
        $this->source->setRequestProperties($this->getProperties());
        return $this->source->initialize();
    }
}
return 'assetstvRemoveProcessor';
