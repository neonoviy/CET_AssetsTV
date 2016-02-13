<?php
/**
* Part of Content Editor Tools. Best with CET_TinyMCE or CET_CKEditor.
* assetsTV shows files, uploaded with assetTV custom TV input. 
* So first, you need to create this TV and set "assetstv" as input type.
* In input type parameters set atvPath e.g. assets/lib/[year]/[id]/
* Create a Resource, upload files. Sort them, add Alt and Description if you want.
* Now you can show uploaded files with this snippet.
* Also you can double click on file in TV to add it to TinyMCE or CKEditor.
*
* Usage [[!assetsTV? &resId=`` &tv=`assetsTV` &tpl=`assetsTV-tpl-file`]]
*
* Parameters:
* - resId — resource ID. (optional)
* - limit
* - types — comma separated list of allowed file types (You can set them in TV properties): 
*   - image (jpg, png, gif, svg),
*   - document (doc, docx, xls, xlsx, ppt, pptx),
*   - archive (zip, rar, 7z, gz),
*   - video (mp4, webm, avi, mkv, wmv),
*   - audio (mp3, ogg, wav, aac, wma),
*   - pdf,
*   - file (default)
* - extensions — comma separated list of allowed extensions
* - execute_types — comma separated list of file types you don't want to show
* - execute_extensions — comma separated list of extensions you don't want to show
* - sortBy — you can sort by: 
*   - index (default, file order in TV), 
*   - filename, 
*   - date, 
*   - type, 
*   - extension, 
*   - alt, 
*   - description, 
*   - size
* - sortDir — ASC or DESC
*
* In tpl you can use fillowing placeholders:
* [[+atv.url]] - path to file
* [[+atv.filename]]
* [[+atv.index]] - file order in TV
* [[+atv.alt]] - set in TV
* [[+atv.description]] - set in TV
* [[+atv.extension]]
* [[+atv.type]]
* [[+atv.bytesize]] - file size in bytes
* [[+atv.size]] - file size in Kb, Mb, Gb etc
* [[+atv.date]] - Unix Time. You can use input modifiers like [[+atv.date:date=`%d %B %Y %H:%M:%S.`]]
* [[+atv.odd]] - odd or even file
*
* version 0.6
*
* author Denis Dyranov (Dyranov.ru)
**/
if(!$tmb_max_height){
$tmb_max_height = 105;
}

if (!function_exists('byteConvert')) {
    function byteConvert($bytes)
    {
        $s = array('B', 'Kb', 'MB', 'GB', 'TB', 'PB');
        $e = floor(log($bytes)/log(1024));
        return sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e))));
    }
}

if(!$tv){
$error = 'no TV';
}
//defaults
if($sortBy==''){ $sortBy = 'index'; }
if($sortDir==''){ $sortDir = 'asc'; }

if($types){ $types = explode(',', $types); }
if($extensions){ $extensions = explode(',', $extensions); }
if($execute_types){ $execute_types = explode(',', $execute_types); }
if($execute_extensions){ $execute_extensions = explode(',', $execute_extensions); }

if(!$tpl){
$error = 'no tpl';
}

$gottpl = $modx->getChunk($tpl);
if(!$gottpl){
  $error = 'tpl "'.$tpl.'" not found';
  return $error;
}

if(!$resId){
$resId = $modx->resource->get('id');
//use this resource id
}

//getting folder
$page = $modx->getObject('modResource', $resId);
$tvvalue = $page->getTVValue($tv);
if(!$tvvalue){
  $error = 'tv "'.$tv.'" not found';
  return $error;
}
$array = json_decode($tvvalue, true);

$tvr = $modx->getObject('modTemplateVar', array(
  'name' => $tv
));

$iprops = $tvr->get('input_properties');
$sourceID = $iprops['atvSource'];
if(empty($sourceID)){
				$sourceID = '1';
				}
$atvPath = $iprops['atvPath'];
$year = date('Y');
$month = date('m');
$day = date('d');
$folder = str_replace("[id]", $resId, $atvPath);
$folder = str_replace("[year]", $year, $folder);
$folder = str_replace("[month]", $month, $folder);
$folder = str_replace("[day]", $day, $folder);

$source = $modx->getObject('sources.modMediaSource', $sourceID);
        if (empty($source)) {
            $sourcefolder = "";
			$error = 'source not found';
 			return $error;
        }
$source->initialize('web');

$sourcefolder = $source->getObjectUrl($folder);

$list = $source->getObjectsInContainer($folder);

//return '<p>'.print_r($list).'</p>';

//getting files
if ( false!==$list ) {
	foreach ( $list as $file ) {
		if ( '.'!=$file && '..'!=$file && '.DS_Store'!=$file && '.htaccess'!=$file && is_dir($file['fullRelativeUrl'])!=true) {
			$file_alt = '';
			$file_title = '';
			$file_index = '9999';
//getting basic type for uploaded by FTP files
			switch ($file['ext']) {
			  case "jpg":
			  case "jpeg":
			  case "png":
			  case "gif":
				  $file_type = 'image';
				  break;
			  default:
				  $file_type = 'file';
			  }
//getting TV values
			if(is_array($array)){
			  foreach ($array as $key => $value){
				  if($key == $file['name']){
					  $file_alt = $value[alt];
					  $file_description = $value[description];
					  $file_index = $value[index];
					  $file_type = $value[type];
				  }
			  }
			}
			$obj['filename'] = $file['name'];
			$obj['index'] = $file_index;
			$obj['url'] = $file['fullRelativeUrl'];
			$obj['extension'] = $file['ext'];
			$obj['alt'] = $file_alt;
			$obj['description'] = $file_description;
			$obj['bytesize'] = $file['size'];
			$obj['size'] = byteConvert($file['size']);;
			$obj['type'] = $file_type;
			$obj['date'] = $file['lastmod'];
			$obj['width'] = $file['image_width'];
            $obj['height'] = $file['image_height'];
			$thumb = str_replace("HTTP_MODAUTH=&", "", $file['thumb']);
			//thumbs works only if you logged in
			$obj['thumb'] = $thumb;
			$obj['thumb_width'] = $file['thumb_width'];
			$obj['thumb_height'] = $file['thumb_height'];
			if ($file['image_width'] != $file['image_height']){
				$tmb_h = $tmb_max_height;
				$tmb_w = $file['image_width']*$tmb_max_height/$file['image_height'];
			}
			if ($file['image_width'] == $file['image_height']){
				$tmb_h = $tmb_max_height;
				$tmb_w = $tmb_max_height;
			}
			$obj['thumb_width'] = $tmb_w;
			$obj['thumb_height'] = $tmb_h;

//filtering
			$show1 = false;
			$show2 = false;
			$show3 = true;
			$show4 = true;

			if(is_array($types)){
				foreach($types as $type){
					if(trim($type) == $obj['type']){ $show1 = true; }
				}
			}else{	$show1 = true; }

			if(is_array($extensions)){
				foreach($extensions as $extension){
					if(trim($extension) == $obj['extension']){ $show2 = true; }
				}
			}else{ $show2 = true; }

			if(is_array($execute_types)){
				foreach($execute_types as $execute_type){
					if(trim($execute_type) == $obj['type']){ $show3 = false; }
				}
			}

			if(is_array($execute_extensions)){
				foreach($execute_extensions as $execute_extension){
					if(trim($execute_extension) == $obj['extension']){ $show4 = false; }
				}
			}

			if($show1 == true && $show2 == true && $show3 == true && $show4 == true){$result[] = $obj;}
		}
	}
}else{
$error = 'list empty';
return $error;
}

//sorting
    foreach ($result as $key => $row) {
        $index_ar[$key]  = $row['index'];
        $name_ar[$key] = $row['filename'];
		$date_ar[$key] = $row['date'];
		$type_ar[$key] = $row['type'];
		$size_ar[$key] = $row['bytesize'];
		$alt_ar[$key] = $row['alt'];
		$extension_ar[$key] = $row['extension'];
		$description_ar[$key] = $row['description'];
    }
	switch ($sortBy) {
		case "index":
		  if(strtolower($sortDir) == 'desc'){
		  array_multisort($index_ar, SORT_DESC, $name_ar, SORT_DESC, $result);
		  }else{
		  array_multisort($index_ar, SORT_ASC, $name_ar, SORT_ASC, $result);
		  }
		break;
		case "date":
		  if(strtolower($sortDir) == 'desc'){
		  array_multisort($date_ar, SORT_DESC, $result);
		  }else{
		  array_multisort($date_ar, SORT_ASC, $result);
		  }
		break;
		case "filename":
		  if(strtolower($sortDir) == 'desc'){
		  array_multisort($name_ar, SORT_DESC, $result);
		  }else{
		  array_multisort($name_ar, SORT_ASC, $result);
		  }
		break;
		case "alt":
		  if(strtolower($sortDir) == 'desc'){
		  array_multisort($alt_ar, SORT_DESC, $name_ar, SORT_DESC, $result);
		  }else{
		  array_multisort($alt_ar, SORT_ASC, $name_ar, SORT_DESC, $result);
		  }
		break;
		case "description":
		  if(strtolower($sortDir) == 'desc'){
		  array_multisort($description_ar, SORT_DESC, $name_ar, SORT_DESC, $result);
		  }else{
		  array_multisort($description_ar, SORT_ASC, $name_ar, SORT_DESC, $result);
		  }
		break;
		case "extension":
		  if(strtolower($sortDir) == 'desc'){
		  array_multisort($extension_ar, SORT_DESC, $result);
		  }else{
		  array_multisort($extension_ar, SORT_ASC, $result);
		  }
		break;
		case "type":
		  if(strtolower($sortDir) == 'desc'){
		  array_multisort($type_ar, SORT_DESC, $result);
		  }else{
		  array_multisort($type_ar, SORT_ASC, $result);
		  }
		break;
		case "size":
		  if(strtolower($sortDir) == 'desc'){
		  array_multisort($size_ar, SORT_DESC, $result);
		  }else{
		  array_multisort($size_ar, SORT_ASC, $result);
		  }
		break;
	}
//limit and output
	$i = 0;
	foreach ($result as $key => $value){
	++$i;
	if($limit){	if($i > $limit) break;	}
	if($i % 2 == 0){$odd = 'odd'; }else{$odd = 'even'; }
		$output .= $modx->getChunk($tpl, array(
		  'atv.index' => $value['index'],
		  'atv.url' => $value['url'],
		  'atv.filename' => $value['filename'],
		  'atv.extension' => $value['extension'],
		  'atv.alt' => $value['alt'],
		  'atv.description' => $value['description'],
		  'atv.bytesize' => $value['bytesize'],
		  'atv.size' => $value['size'],
		  'atv.type' => $value['type'],
		  'atv.date' => $value['date'],
		  'atv.odd' => $odd,
		  'atv.width' => $value['width'],
		  'atv.height' => $value['height'],
		  'atv.thumb' => $value['thumb'],
		  'atv.thumb_height' => $value['thumb_height'],
		  'atv.thumb_width' => $value['thumb_width'],
		  'atv.rel' => $rel
		));
	}
if($error){
return $error;
}else{
return $output;
}