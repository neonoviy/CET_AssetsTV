<div id="tv-input-properties-form{$tv}"></div>
{literal}

<script type="text/javascript">
// <![CDATA[
var params = {
{/literal}{foreach from=$params key=k item=v name='p'}
 '{$k}': '{$v|escape:"javascript"}'{if NOT $smarty.foreach.p.last},{/if}
{/foreach}{literal}
};
var oc = {'change':{fn:function(){Ext.getCmp('modx-panel-tv').markDirty();},scope:this}};
MODx.load({
    xtype: 'panel'
    ,layout: 'form'
    ,autoHeight: true
    ,cls: 'form-with-labels'
    ,border: false
    ,labelAlign: 'top'
    ,items: [{
        xtype: 'combo-boolean'
        ,fieldLabel: _('required')
        ,description: MODx.expandHelp ? '' : _('required_desc')
        ,name: 'inopt_allowBlank'
        ,hiddenName: 'inopt_allowBlank'
        ,id: 'inopt_allowBlank{/literal}{$tv}{literal}'
        ,value: !(params['allowBlank'] == 0 || params['allowBlank'] == 'false')
        ,width: 200
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_allowBlank{/literal}{$tv}{literal}'
        ,html: _('required_desc')
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: 'atvSource'
        ,description: 'atvSource'
        ,name: 'inopt_atvSource'
        ,id: 'inopt_atvSource{/literal}{$tv}{literal}'
        ,value: params['atvSource'] || ''
        ,anchor: '98%'
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_atvSource{/literal}{$tv}{literal}'
        ,html: 'Media Source Id. If none, 1 will be used'
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: 'atvPath'
        ,description: 'atvPath'
        ,name: 'inopt_atvPath'
        ,id: 'inopt_atvPath{/literal}{$tv}{literal}'
        ,value: params['atvPath'] || ''
        ,anchor: '98%'
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_atvPath{/literal}{$tv}{literal}'
        ,html: 'Folder to store files. You can use [id], [year], [month], [day], [alias] placehilders. E.g. assets/lib/[year]/[id]/'
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: 'allowed_file_types'
        ,description: 'allowed_file_types'
        ,name: 'inopt_allowed_file_types'
        ,id: 'inopt_allowed_file_types{/literal}{$tv}{literal}'
        ,value: params['allowed_file_types'] || ''
        ,anchor: '98%'
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_allowed_file_types{/literal}{$tv}{literal}'
        ,html: 'Allowed file extensions or mime-types. e.g. images/*,.zip,.rar,.7z,.gz,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf  Modx settings by default.'
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: 'max_file_size'
        ,description: 'max_file_size'
        ,name: 'inopt_max_file_size'
        ,id: 'inopt_max_file_size{/literal}{$tv}{literal}'
        ,value: params['max_file_size'] || ''
        ,anchor: '98%'
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_max_file_size{/literal}{$tv}{literal}'
        ,html: 'Maximum file size to upload in Mb. Modx settings by default.'
        ,cls: 'desc-under'
    },{
        xtype: 'textarea'
        ,fieldLabel: 'file_type_switch'
        ,description: 'file_type_switch'
        ,name: 'inopt_file_type_switch'
        ,id: 'inopt_file_type_switch{/literal}{$tv}{literal}'
        ,value: params['file_type_switch'] || ''
        ,anchor: '98%'
        ,listeners: oc
        ,grow: true
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_file_type_switch{/literal}{$tv}{literal}'
        ,html: 'Javascript. You can use vars: url, filename, alt, description, type, extension, size, width (for images).<br/>Delete all, save and reload to get default.<br/>If alt undefined, filename will be used.'
        ,cls: 'desc-under'
    },{
        xtype: 'textarea'
        ,fieldLabel: 'rte_insert_switch'
        ,description: 'rte_insert_switch'
        ,name: 'inopt_rte_insert_switch'
        ,id: 'inopt_rte_insert_switch{/literal}{$tv}{literal}'
        ,value: params['rte_insert_switch'] || ''
        ,anchor: '98%'
        ,listeners: oc
        ,grow: true
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_rte_insert_switch{/literal}{$tv}{literal}'
        ,html: 'Used to insert data to RTE by double click on file.<br/>Javascript. You can use vars: url, filename, alt, description, type, extension, size, width (for images).<br/>Delete all, save and reload to get default.<br/>If alt undefined, filename will be used.'
        ,cls: 'desc-under'
    },{
        xtype: 'textfield'
        ,fieldLabel: 'tmb_max_size'
        ,description: 'tmb_max_size'
        ,name: 'inopt_tmb_max_size'
        ,id: 'inopt_tmb_max_size{/literal}{$tv}{literal}'
        ,value: params['tmb_max_size'] || ''
        ,anchor: '98%'
        ,listeners: oc
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_tmb_max_size{/literal}{$tv}{literal}'
        ,html: 'Maimum size of thumbnail for images. Number only, no "px".'
        ,cls: 'desc-under'
    },{
        xtype: 'textarea'
        ,fieldLabel: 'insert_with_thumb'
        ,description: 'insert_with_thumb'
        ,name: 'inopt_insert_with_thumb'
        ,id: 'inopt_insert_with_thumb{/literal}{$tv}{literal}'
        ,value: params['insert_with_thumb'] || ''
        ,anchor: '98%'
        ,listeners: oc
        ,grow: true
    },{
        xtype: MODx.expandHelp ? 'label' : 'hidden'
        ,forId: 'inopt_insert_with_thumb{/literal}{$tv}{literal}'
        ,html: 'Used to insert image with thubmnail to RTE. For lightboxes.<br/> Javascript. You can use vars: url, filename, alt, description, type, extension, size, width, height, tmb_width, tmb_height.<br/>Delete all, save and reload to get default.<br/>If alt undefined, filename will be used.'
        ,cls: 'desc-under'
    }]
    ,renderTo: 'tv-input-properties-form{/literal}{$tv}{literal}'
});

if (Ext.get('inopt_file_type_switch{/literal}{$tv}{literal}').dom.value == '') {
	var file_type_switch = Ext.getCmp('inopt_file_type_switch{/literal}{$tv}{literal}').setValue('case \'jpg\':\r\ncase \'png\':\r\ncase \'gif\':\r\ncase \'jpeg\':\r\ncase \'svg\':\r\n return \'image\';\r\n break;\r\ncase \'zip\':\r\ncase \'rar\':\r\ncase \'7z\':\r\ncase \'gz\':\r\n return \'archive\';\r\n break;\r\ncase \'doc\':\r\ncase \'docx\':\r\ncase \'xls\':\r\ncase \'xlsx\':\r\ncase \'ppt\':\r\ncase \'pptx\':\r\n return \'document\';\r\n break;\r\ncase \'mp3\':\r\ncase \'ogg\':\r\ncase \'wav\':\r\ncase \'aac\':\r\ncase \'wma\':\r\n return \'audio\';\r\n break;\r\ncase \'mp4\':\r\ncase \'webm\':\r\ncase \'avi\':\r\ncase \'mkv\':\r\ncase \'wmv\':\r\n return \'video\';\r\n break;\r\ncase \'pdf\':\r\n return \'pdf\';\r\n break;\r\ndefault:\r\n return \'file\';');
}


if (Ext.get('inopt_rte_insert_switch{/literal}{$tv}{literal}').dom.value == '') {
	var rte_insert_switch = Ext.getCmp('inopt_rte_insert_switch{/literal}{$tv}{literal}').setValue('case \'image\':\r\n if (width > 900) { var ifbig = \'class=\"img-responsive\"\'; }\r\n content = \'<img src=\"\'+url+\'\" alt=\"\'+alt+\'\" width=\"\'+width+\'\" height=\"\'+height+\'\" \'+ifbig+\' \/>\';\r\n break;\r\ncase \'archive\':\r\n content = \'<p><span class=\"download\"><a href=\"\'+url+\'\"\/>\'+alt+\' (\'+extension+\', \'+size+\')<\/a><\/span><\/p>\';\r\n break;\r\ncase \'document\':\r\n content = \'<p><span class=\"download\"><a href=\"\'+url+\'\"\/>\'+alt+\' (\'+extension+\', \'+size+\')<\/a><\/span><\/p>\';\r\n break;\r\ncase \'audio\':\r\n content = \'<p><span class=\"download\"><a href=\"\'+url+\'\"\/>\'+alt+\' (\'+extension+\', \'+size+\')<\/a><\/span><\/p>\';\r\n break;\r\ncase \'video\':\r\n content = \'<p><span class=\"download\"><a href=\"\'+url+\'\"\/>\'+alt+\' (\'+extension+\', \'+size+\')<\/a><\/span><\/p>\';\r\n break;\r\ncase \'pdf\':\r\n content = \'<p><span class=\"download\"><a href=\"\'+url+\'\"\/>\'+alt+\' (\'+extension+\', \'+size+\')<\/a><\/span><\/p>\';\r\n break;\r\ndefault:\r\n content = \'<p><span class=\"download\"><a href=\"\'+url+\'\"\/>\'+alt+\' (\'+extension+\', \'+size+\')<\/a><\/span><\/p>\';');
}

if (Ext.get('inopt_tmb_max_size{/literal}{$tv}{literal}').dom.value == '') {
	var tmb_max_size = Ext.getCmp('inopt_tmb_max_size{/literal}{$tv}{literal}').setValue('120');
}

if (Ext.get('inopt_insert_with_thumb{/literal}{$tv}{literal}').dom.value == '') {
	var insert_with_thumb = Ext.getCmp('inopt_insert_with_thumb{/literal}{$tv}{literal}').setValue('<a href=\"\'+url+\'\" class=\"fancy\"><img src=\"\'+url+\'\" width=\"\'+tmb_width+\'\" height=\"\'+tmb_height+\'\" alt=\"\'+alt+\'\" title=\"\'+description+\'\" class=\"thumb\"/></a>');
}


// ]]>
</script>
{/literal}
