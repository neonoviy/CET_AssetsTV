<!--welcome to hell-->
{if $resource_id == '0'}
<p class="nosave">Save resource first</p>
{else}

<div id="dzcontainer{$tv->id}" class="dzcontainer">
<div id="dzinst{$tv->id}">

<div id="tv{$tv->id}-cb"></div>
<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="hidden" class="textfield"
	value="{$tv->get('value')|escape}"
	{$style}
	tvtype="{$tv->type}"
/>



<form class="dropzone clearfix dz-clickable" id="dz{$tv->id}inst" action="{$assetsPath}components/CET_assetstv/connector.php?action=browser/file/upload" onchange="form2json({$tv->id});">
  <input type="hidden" name="path" id="dz{$tv->id}" value="{$folder}"/>
  <input type="hidden" name="sourceID" id="sourceID" value="{$sourceID}"/>
   <div class="fallback">
  	<input name="file" type="file" multiple />
  </div>
</form>

<script src="{$assetsPath}components/CET_assetstv/js/jquery-2.1.3.min.js"></script>
<script src="{$assetsPath}components/CET_assetstv/js/dropzone.js"></script>
<script src="{$assetsPath}components/CET_assetstv/js/jquery-ui.js"></script>
<script src="{$assetsPath}components/CET_assetstv/js/assetsTv.js"></script>

<link rel="stylesheet" href="{$assetsPath}components/CET_assetstv/dropzone-assetstv.css">


<!--dropzone preview container template-->
<div id="template-container" style="display: none;">

<div class="dz-preview dz-file-preview">
<input type="checkbox" class="dzchkfile"/>

	<div class="dz-image" id="data-dz-ext" >
		<div class="icons">
						<div class="dz-success-mark"></div>
			<div class="dz-error-mark"></div>
			<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
			
			<div class="delete"></div>
			
			<a href="{$folder}" class="link_to_file" target="_blank" onclick="copyToClipboard( $(this).attr('href') ); return false;"></a>
			<div class="insert_with_tmb" target="_blank" ></div>
			<div class="insert_sel" target="_blank" style="display: none;"></div>
		</div><!--end icons-->
		
		<img data-dz-thumbnail class="file-icon"/>
		
	</div><!--end dz-image-->
	
  <div class="dz-details">
  <span class="extension" data-dz-extension ></span>
    <div class="dz-filename">
    	<input type="text" name="file.name" class="name" id="file.name" value="" disabled data-dz-name/><br/>
    </div>
		<div class="dz-size"><span data-dz-size></span><span class="width_height"></span> 
		</div>
	</div>
  <div class="dz-error-message"><span data-dz-errormessage></span></div>
	<div class="additional">
		<input type="text" name="file.alt" class="alt" id="" value="" placeholder="Alt"/><br/>
		<textarea name="file.description" class="description" id="" value="" placeholder="Description"/></textarea>
			
		<input type="hidden" name="file.index" class="index" id="" value="" />
		<input type="hidden" name="file.type" class="type" id="" value="" /><br/>
	</div>
</div><!--end dz-preview-->
</div><!--end template-container-->
<div class="dzlinks">
<label class="dzselectall"><input type="checkbox" id="checkAll{$tv->id}"/> Select all</label>
<span onclick="javascript: insertSelected{$tv->id}();" class="dzselect">Insert selected</span>
<span id="select{$tv->id}" class="dzselect">Upload file</span> 
<span onclick="javascript: switchView({$tv->id});" class="dztoggle">Toggle view</span>
</div>
<p id="folder">{$folder} in {$sourceName}. <span id="cet_to_ck"></span></p>

</div><!--end dzinstance-->
</div><!--end dzcontainer-->
<!--
<form method="post" action=""></form>
<input type="button" name="add" value="add snippet" onclick="inserttest{$tv->id}();" />
</form>
-->
<script type="text/javascript">
Dropzone.autoDiscover = false;

$(document).ready(function() {
waitForElement{$tv->id}();
});

function waitForElement{$tv->id}(){
    if(typeof MODx.siteId !== "undefined"){
    	//console.log('defined'+MODx.siteId);
    		if(MODx.siteId == 0){ $('#folder').append(' !!!MODx.siteId=0 REFRESH YOUR SESSION!!!'); }
        dzon{$tv->id}();
    }
    else{
        setTimeout(function(){
            waitForElement{$tv->id}();
        },250);
    }
}

function dzon{$tv->id}(){
//console.log('init: dz{$tv->id} '+MODx.siteId);
	$('#dz{$tv->id}inst').sortable({
	    items: ".dz-preview",
	    stop: function( event, ui ) { form2json({$tv->id}); },
	    delay: 150,
	    distance: 5,
	});


var dz{$tv->id}instDropzone = new Dropzone("#dz{$tv->id}inst", {
		clickable: '#select{$tv->id}',
		acceptedFiles: '{$allowed_file_types}',
		dictDefaultMessage: "Drop files here to upload",
		headers: {
		    "HTTP_MODAUTH": MODx.siteId
		},
		debug: true,
		maxFilesize: {$maxsize},
		createImageThumbnails: true,
		thumbnailWidth: 128,
		thumbnailHeight: 128,
		previewTemplate: document.querySelector('#template-container').innerHTML,
		removedfile: function(file) {
		    //console.log('del: {$folder}'+file.name);
		    var name = '{$folder}'+file.name;
		    $.ajax({
		        type: 'POST',
		        headers: {
		            'HTTP_MODAUTH':MODx.siteId,
		        },
		        url: '{$assetsPath}components/CET_assetstv/connector.php',
		        data: {
		        action:'browser/file/remove'
		        ,HTTP_MODAUTH: MODx.siteId,
		        sourceID: '{$sourceID}', 
		        file: '{$folder}'+file.name, prependPath: 0},
		        dataType: 'json',
		        success: function(data){
		                 //console.log(data);

											var _ref;
											return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;

		              },
		        complete: function(data){
		        //console.log('Here');
		    	    form2json({$tv->id});
		      			  }
		    });
		              },
		              
		init: function() {

    this.on("addedfile", function(file) {
    
<!--file name-->    
    		$(file.previewElement).prop('id',  file.name);
				$(file.previewElement).find('.name').val(file.name);    
<!--extension-->     
		    var extension = file.name.substr( (file.name.lastIndexOf('.') +1) );
		    console.log(extension);
<!--type-->  
		    var filetype = setfiletype{$tv->id}(extension);
		    $(file.previewElement).find('.extension').text(extension);
		    $(file.previewElement).find('.extension').addClass(extension);
		    if (filetype != 'image') {
				    	this.emit("thumbnail", file, "{$assetsPath}components/CET_assetstv/img/file-icon-mask.png");
				}
				if (filetype) {
					$(file.previewElement).find('.type').val(filetype);
				}else {
					$(file.previewElement).find('.type').val(file.type);
				}
				$(file.previewElement).addClass(filetype);
<!--path and size-->  
				var filepath = '{$sourcefolder}'+file.name;
				//console.log(file);
				var size = bytesToSize(file.size);
<!--additionalinfo-->  
				var additional = getadditional({$tv->id}, file.name);
				$(file.previewElement).find('.alt').val(additional['alt']);
				$(file.previewElement).find('.alt').prop('id', file.name+'-alt');
				$(file.previewElement).find('.alt').attr('name', file.name+'-alt');	
	
				$(file.previewElement).find('.description').prop('id', file.name);
				$(file.previewElement).find('.description').val(additional['description']);
				$(file.previewElement).find('.description').prop('id', file.name+'-description');
				$(file.previewElement).find('.description').attr('name', file.name+'-description');

				$(file.previewElement).find('.index').prop('id', file.name);
				if (additional['index']) {
					$(file.previewElement).find('.index').val(additional['index']);
				}

				$(file.previewElement).find('.link_to_file').prop('href', filepath);
				$(file.previewElement).find('.inserttest').prop('href', file.name);
				
<!--delete button-->				
				var _this = this;
				var removeButton = $(file.previewElement).find('.delete');
				removeButton.bind("click", function(e) {
				          // Make sure the button click doesn't submit the form:
				          e.preventDefault();
				          e.stopPropagation();
				
				          // Remove the file preview.
				          if (confirm("Delete?")) {
				          _this.removeFile(file);
				          form2json({$tv->id});
				          }
				          
				        });
							
<!--Event to insert to TinyMCE-->  
//				  file.previewElement.addEventListener("dblclick", function() {
//				    inserttomce{$tv->id}(filepath, filetype, file.name, extension, size, file.width, file.height);
//				  });
				  $(file.previewElement).find('.file-icon').on("dblclick", function() {
				    inserttomce{$tv->id}(filepath, filetype, file.name, extension, size, file.width, file.height);
				  });
				  $(file.previewElement).find('.insert_sel').click(function() {
				    inserttomce{$tv->id}(filepath, filetype, file.name, extension, size, file.width, file.height);
				    return false;
				   });
				  if (filetype == 'image') {
				  $(file.previewElement).find('.insert_with_tmb').click(function() {
				    insertfancy{$tv->id}(filepath, filetype, file.name, extension, size, file.width, file.height);
				    return false;
				  });
				  }else{
				  $(file.previewElement).find('.insert_with_tmb').hide();
				  }
				  
    });
    this.on("success", function(file, responseText) {
                //console.log(responseText);
                if(responseText['success'] != true){
              	  thisDropzone{$tv->id}.emit("error", file, responseText['message']);
                }
                form2json({$tv->id});
                
            });

    this.on("sending", function(file, xhr, formData) {
      // add headers with xhr.setRequestHeader() or
      // form data with formData.append(name, value);
      	
      	//console.log('send: '+MODx.siteId);
      	formData.append("HTTP_MODAUTH", MODx.siteId);
      //	xhr.setRequestHeader('HTTP_MODAUTH', MODx.siteId);
      //	
      	
    });
<!--добавляем размеры картинки когда она уже загружена-->
    this.on("thumbnail", function(file) {
			    var extension = file.name.substr( (file.name.lastIndexOf('.') +1) );
			    var filetype = setfiletype{$tv->id}(extension);
			    if (filetype == 'image') {
			    		$(file.previewElement).find('.width_height').text(', '+file.width+'×'+file.height+' px');
			    	}
   	 });
    }
	});
	
	
<!--Добавляем что было загружено раньше-->
	thisDropzone{$tv->id} = dz{$tv->id}instDropzone;

	$.ajax({
		url:"{$assetsPath}components/CET_assetstv/connector.php",
		type:"POST",
		headers: {
		    'HTTP_MODAUTH':MODx.siteId,
		},
		data: {
			action:'browser/file/getlist'
			,HTTP_MODAUTH: MODx.siteId
			,id: '{$folder}'
			,tv_id: '{$tv->name}'
			,resource_id: '{$resource_id}'
			,sourceID: '{$sourceID}'
		},
		dataType:"json",
		}).done(function(data{$tv->id}) {
		//console.log({$tv->id});
		//console.log(MODx.siteId);
		//console.log(data{$tv->id});
	    $.each(data{$tv->id}, function(key,value){
	        var mockFile = { name: value.name, size: value.size, width: value.width, height: value.height, ext: value.ext, url: value.url };
	        thisDropzone{$tv->id}.emit("addedfile", mockFile);
	        var extension = value.name.substr( (value.name.lastIndexOf('.') +1) );
	        var filetype = setfiletype{$tv->id}(extension);
	        if (filetype != 'image') {
	              thisDropzone{$tv->id}.emit("thumbnail", mockFile, "{$assetsPath}components/CET_assetstv/img/file-icon-mask.png");
	            }else{
	            	 thisDropzone{$tv->id}.emit("thumbnail", mockFile, "{$sourcefolder}"+value.name);
	            }
	        thisDropzone{$tv->id}.emit("complete", mockFile);
	        var existingFileCount = existingFileCount+1;
	        thisDropzone{$tv->id}.options.maxFiles = thisDropzone{$tv->id}.options.maxFiles - existingFileCount;
	    });
	});
<!--Добавили что было загружено раньше-->
}

function setfiletype{$tv->id}(extension) {
	extension = extension.toLowerCase();
	switch(extension) {
	{$file_type_switch}
	    }
}

function inserttomce{$tv->id}(url, type, filename, extension, size, width, height) {
	if (width == undefined) { width = ''; }
	var tvid = '{$tv->id}';
	var additional = getadditional(tvid, filename);
	var alt = additional['alt'];
	if (!alt) { alt = filename; }
	var description = additional['description'];
	var content = '';
	switch(type) {
		{$rte_insert_switch}
	    }
	    
	if ($("#cet_selector").val()) {
		var type = $("#cet_selector").attr('class');
		var name =  $("#cet_selector").val();
			if (type == "cet_rte") {
					var currentrte = CKEDITOR.instances[name].insertHtml(content);
					console.log("insert");
			}

	}else{
		if(typeof tinyMCE != 'undefined'){
		tinyMCE.get("ta").execCommand('mceInsertContent',false,content);
		console.log("insert");
		}
		if(typeof CKEDITOR != 'undefined'){
		CKEDITOR.instances.ta.insertHtml(content);
		console.log("insert");
		}
	}
}

function insertfancy{$tv->id}(url, type, filename, extension, size, width, height) {
var max='{$tmb_max_size}';
var file = getadditional({$tv->id}, filename);
var alt = file['alt'];
var description = file['description'];
if(width > height){
	tmb_width = max;
	tmb_height = Math.round(height*max/width);
}
if(height > width){
	tmb_height = max;
	tmb_width = Math.round(width*max/height);
}
if(height == width){
	tmb_height = max;
	tmb_width = max;
}
var content2='{$insert_with_thumb}';
	if ($("#cet_selector").val()) {
		var type = $("#cet_selector").attr('class');
		var name =  $("#cet_selector").val();
			if (type == "cet_rte") {
					var currentrte = CKEDITOR.instances[name].insertHtml(content2);
			}

	}else{
		if(typeof tinyMCE != 'undefined'){
		tinyMCE.get("ta").execCommand('mceInsertContent',false,content2);
		}
		if(typeof CKEDITOR != 'undefined'){
		CKEDITOR.instances.ta.insertHtml(content2);
		}
}
}

$("#checkAll{$tv->id}").change(function () {
    $("#dzinst{$tv->id} .dzchkfile").prop('checked', $(this).prop("checked"));
});

function insertSelected{$tv->id}() {
$("#dzinst{$tv->id} .dzchkfile:checked").each(function() {
    $(this).parent().find('.insert_sel').click();
});
}

function inserttest{$tv->id}() {
console.log('tst');
var content='[[!assetsTV? &tv=`{$tv->name}` &tpl=`your_tpl_chunk`]]';
if(typeof tinyMCE != 'undefined'){
tinyMCE.get("ta").execCommand('mceInsertContent',false,content);
}
if(typeof CKEDITOR != 'undefined'){

CKEDITOR.instances.ta.insertHtml(content);


}
}

</script>
{/if}