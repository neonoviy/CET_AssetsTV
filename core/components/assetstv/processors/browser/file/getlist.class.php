<?php
/**
 * Get a list of directories and files, sorting them first by folder/file and
 * then alphanumerically.
 *
 * @param string $id The path to grab a list from
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 * @param boolean $hideFiles (optional) If true, will not display files.
 * Defaults to false.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package CET_AssetsTV
 * @subpackage processors.browser.directory
 */
class assetstvGetListProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;

    public function getLanguageTopics() {
        return array('file');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'id' => '',
        ));
        $dir = $this->getProperty('id');
        if (empty($dir) || $dir == 'root') {
            $this->setProperty('id','');
        } else if (strpos($dir, 'n_') === 0) {
            $dir = substr($dir, 2);
        }
        $this->setProperty('dir',$dir);
        return true;
    }

    public function process() {
    $tv = $_POST['tv_id'];
    
    
    $resId = $_POST['resource_id'];
    $page = $this->modx->getObject('modResource', $resId);
    $tvvalue = $page->getTVValue($tv);
    $nowTVarray = json_decode($tvvalue, true);
    
        if (!$this->getSource()) {
            return $this->modx->toJSON(array());
        }
        if (!$this->source->checkPolicy('list')) {
            return $this->modx->toJSON(array());
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();

        $list = $this->source->getContainerList($this->getProperty('dir'));
        
        foreach ( $list as $file ) {
                   if ( '.'!=$file && '..'!=$file && '.DS_Store'!=$file && '.htaccess'!=$file && is_dir($storeFolder.$file)!=true) {            
        						//If there is no file in TV, it was uploaded another way. Let it be in the end of the list.
                   $obj['index'] = '9999';
                   foreach ($nowTVarray as $key => $value){
                   	if($key == $file['text']){
                   		$obj['index'] = $value['index'];
                   	}
                   }
                   //Define picture size
                   $path_info = pathinfo($file['url']);
                   $extension = strtolower($path_info['extension']);
                   if ($extension == 'jpg' || 
                   		$extension == 'png' ||
                   		$extension == 'gif' ||
                    		$extension == 'jpeg' 
                   		) {
                   	list($width, $height, $type, $attr) = getimagesize($file['path']);
                   	$obj['width'] = $width;
                   	$obj['height'] = $height;
                   }
                       $obj['name'] = $file['text'];
                       $obj['url'] = $file['url'];
                       $obj['obj'] = $file;
                       $obj['ext'] = $path_info['extension'];
                       
                       $obj['size'] = filesize($file['path']);
        
                       $result[] = $obj;
                   }
               }
        foreach ($result as $key => $row) {
            $index[$key]  = $row['index'];
            $name[$key] = $row['name'];
        }
        array_multisort($index, SORT_ASC, $name, SORT_ASC, $result);
        
        return $this->modx->toJSON($result);
    }

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
return 'assetstvGetListProcessor';
