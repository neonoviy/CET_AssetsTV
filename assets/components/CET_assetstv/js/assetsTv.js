
function getadditional(tvid, filename) {
	var output = {};
	var nowvalue = $('#tv'+tvid).val();
	if (nowvalue != '') {
			 var now_object = JSON.parse(nowvalue);
				$.each(now_object, function(key,value){
					if (key == filename) {
						output['alt'] = value['alt'];
						output['description'] = value['description'];
						output['index'] = value['index'];
					}
				});
		}
	if (output != '') {
		return output;
	}
}


function form2json(tvid) {
	var now_object = {};
	var nuber = $('#dz'+tvid+'inst .dz-preview:not(.dz-error)').length;
	if(nuber > 0){
		$('#dz'+tvid+'inst .dz-preview:not(.dz-error)').each(function(){
		file = $(this).attr('id');
		alt = $(this).find('.alt').val();
		description = $(this).find('.description').val();
		index = $(this).index( '.dz-preview' );
		type = $(this).find('.type').val();
		now_object[file] = { 'alt' : alt, 'description': description, 'index': index, 'type': type};
	  var newvalue = JSON.stringify(now_object, null, 2);
	  $('#tv'+tvid).val(newvalue);
		});
	}else{
		$('#tv'+tvid).val('');
	}
}

function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};

function copyToClipboard(text) {
  window.prompt("Copy to clipboard: Ctrl+C, Enter", text);
}

function switchView(tvid){
var $panel = $('#assetsTV-bottom-panel'+tvid);
if($panel.length){
		var element = $("#dzinst"+tvid).detach();
		$("#dzcontainer"+tvid).append(element);
		$("#assetsTV-bottom-panel"+tvid).remove();
	}else{
		var element = $("#dzinst"+tvid).detach();
		var $newdiv1 = $( "<div id=\"assetsTV-bottom-panel"+tvid+"\" class=\"assetsTV-bottom-panel\"/>" );
		$("body").append($newdiv1);
		$("#assetsTV-bottom-panel"+tvid).append(element);
	}
}