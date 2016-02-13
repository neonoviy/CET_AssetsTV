<?php
/**
 * @var modX $this->modx
 * @package modx
 * @subpackage processors.element.tv.renders.mgr.input
 */
class modTemplateVarInputRenderAssetstv extends modTemplateVarInputRender {
    public function getTemplate() {
        return $this->modx->getOption('core_path').'components/assetstv/elements/tv/input/tpl/assetstv.tpl';
    }
    public function process($val,array $params = array()) {
 
       $value = explode("||",$val);
       $default = explode("||",$this->tv->get('default_text'));

				$resId = $this->modx->resource->get('id');
				$resAlias = $this->modx->resource->get('alias');
				$resDate = strtotime($this->modx->resource->get('createdon'));
				
				$sourceID = $params['atvSource'];
				if(empty($sourceID)){
				$sourceID = '1';
				}
				$basepath = $params['atvPath'];
				$year = date('Y', $resDate);
				$month = date('m', $resDate);
				$day = date('d', $resDate);
				
				$folder = str_replace("[id]", $resId, $basepath);
				$folder = str_replace("[alias]", $resAlias, $folder);
				$folder = str_replace("[year]", $year, $folder);
				$folder = str_replace("[month]", $month, $folder);
				$folder = str_replace("[day]", $day, $folder);
		     
				$allowed_file_types = $params['allowed_file_types'];
				if (!$allowed_file_types) {
					$allowed_file_types = $this->modx->getOption('extensions', $sp, '');
					if (empty($allowed_file_types)) {
					    $allowed_file_types = $this->modx->getOption('upload_files',null,'');
					}
					$aftarr = explode(',', $allowed_file_types);
					$allowed_file_types = "";
					foreach ($aftarr as $key => $value) {
						$allowed_file_types .= ",.".$value;
					}
				}
				
				$maxsize = $params['max_file_size'];
				if (!$maxsize){	
					$maxsize = $this->modx->getOption('maxsize',$sp,'');
					if (empty($maxsize)) {
				    $maxsize = $this->modx->getOption('upload_maxsize',null,'');
					}
					$maxsize = $maxsize / 1048576;
				}
				
							
				$file_type_switch = $params['file_type_switch'];
				if(!$file_type_switch){
					$file_type_switch = "case 'jpg':\r\ncase 'png':\r\ncase 'gif':\r\ncase 'jpeg':\r\ncase 'svg':\r\n return 'image';\r\n break;\r\ncase 'zip':\r\ncase 'rar':\r\ncase '7z':\r\ncase 'gz':\r\n return 'archive';\r\n break;\r\ncase 'doc':\r\ncase 'docx':\r\ncase 'xls':\r\ncase 'xlsx':\r\ncase 'ppt':\r\ncase 'pptx':\r\n return 'document';\r\n break;\r\ncase 'mp3':\r\ncase 'ogg':\r\ncase 'wav':\r\ncase 'aac':\r\ncase 'wma':\r\n return 'audio';\r\n break;\r\ncase 'mp4':\r\ncase 'webm':\r\ncase 'avi':\r\ncase 'mkv':\r\ncase 'wmv':\r\n return 'video';\r\n break;\r\ncase 'pdf':\r\n return 'pdf';\r\n break;\r\ndefault:\r\n return 'file';";
				}
				
				$rte_insert_switch = $params['rte_insert_switch'];
				if(!$rte_insert_switch){
					$rte_insert_switch = "case 'image':\r\n if (width > 900) { var set_max_width = 'width=\"900px\"'; }\r\n content = '<img src=\"'+url+'\" alt=\"'+alt+'\" '+set_max_width+' \/>';\r\n break;\r\ncase 'archive':\r\n content = '<p><span class=\"download\"><a href=\"'+url+'\"\/>'+alt+' ('+extension+', '+size+')<\/a><\/span><\/p>';\r\n break;\r\ncase 'document':\r\n content = '<p><span class=\"download\"><a href=\"'+url+'\"\/>'+alt+' ('+extension+', '+size+')<\/a><\/span><\/p>';\r\n break;\r\ncase 'audio':\r\n content = '<p><span class=\"download\"><a href=\"'+url+'\"\/>'+alt+' ('+extension+', '+size+')<\/a><\/span><\/p>';\r\n break;\r\ncase 'video':\r\n content = '<p><span class=\"download\"><a href=\"'+url+'\"\/>'+alt+' ('+extension+', '+size+')<\/a><\/span><\/p>';\r\n break;\r\ncase 'pdf':\r\n content = '<p><span class=\"download\"><a href=\"'+url+'\"\/>'+alt+' ('+extension+', '+size+')<\/a><\/span><\/p>';\r\n break;\r\ndefault:\r\n content = '<p><span class=\"download\"><a href=\"'+url+'\"\/>'+alt+' ('+extension+', '+size+')<\/a><\/span><\/p>';";
				}
				
				$tmbmaxsize = $params['tmb_max_size'];
				if(!$tmbmaxsize){
					$tmbmaxsize='120';
				}
				
				$insert_with_thumb = $params['insert_with_thumb'];
				if(!$insert_with_thumb){
					$insert_with_thumb = "<a href=\"'+url+'\" class=\"fancy\"><img src=\"'+url+'\" width=\"'+tmb_width+'\" height=\"'+tmb_height+'\" alt=\"'+alt+'\" title=\"'+description+'\" class=\"thumb\"/></a>";
					}
					
        $this->setPlaceholder('cbdefaults',implode(',',$defaults));
        $this->setPlaceholder('resource_id',$resId);
        $this->setPlaceholder('sourceID',$sourceID);
        $this->setPlaceholder('folder',$folder);
        $this->setPlaceholder('allowed_file_types',$allowed_file_types);
        $this->setPlaceholder('maxsize',$maxsize);
        $this->setPlaceholder('file_type_switch',$file_type_switch);
        $this->setPlaceholder('rte_insert_switch',$rte_insert_switch);
        $this->setPlaceholder('tmb_max_size',$tmbmaxsize);
        $this->setPlaceholder('insert_with_thumb',$insert_with_thumb);
        
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = $this->modx->getObject('modMediaSource', $sourceID);
        if (empty($this->source) || !$this->source->getWorkingContext()) {
            $sourcefolder = "";
        }
        $this->source->initialize();
        $sourcefolder = $this->source->getObjectUrl($folder);
				$this->setPlaceholder('sourcefolder',$sourcefolder);
				$sourceName = $this->source->name;
				$this->setPlaceholder('sourceName',$sourceName);
				$assetspath = $this->modx->getOption('assets_url');
				$this->setPlaceholder('assetsPath',$assetspath);
    }
}
return 'modTemplateVarInputRenderAssetstv';