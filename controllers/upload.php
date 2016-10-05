<?php 

class SPDOWNLOAD_CTRL_Upload extends OW_ActionController
{
	public function index()
	{
		$document = OW::getDocument();

		$document->addStyleSheet(OW::getPluginManager()->getPlugin('spdownload')->getStaticCssUrl() . 'style.css');
		$plugin = OW::getPluginManager()->getPlugin('spdownload');

		$document->addScript($plugin->getStaticJsUrl().'jquery.ui.widget.js');
		$document->addScript($plugin->getStaticJsUrl().'load-image.all.min.js');
		$document->addScript($plugin->getStaticJsUrl().'canvas-to-blob.min.js');
		$document->addScript($plugin->getStaticJsUrl().'jquery.blueimp-gallery.min.js');
		$document->addScript($plugin->getStaticJsUrl().'jquery.iframe-transport.js');
		$document->addScript($plugin->getStaticJsUrl().'jquery.fileupload.js');
		$document->addScript($plugin->getStaticJsUrl().'jquery.fileupload-process.js');
		$document->addScript($plugin->getStaticJsUrl().'jquery.fileupload-image.js');
		$document->addScript($plugin->getStaticJsUrl().'jquery.fileupload-audio.js');
		$document->addScript($plugin->getStaticJsUrl().'jquery.fileupload-video.js');
		$document->addScript($plugin->getStaticJsUrl().'jquery.fileupload-validate.js');
		
		$urlUploadHandler = OW::getRouter()->urlForRoute('spdownload.upload_file');
        $path = $plugin->getUserFilesUrl();
        $loadimage = $plugin->getStaticUrl().'img/' . 'loading.gif';
        
        $script = "
			$(document).on('click', '#file_btn_browse', function(){
	        	$(function () {
	                'use strict';
	                var url = '$urlUploadHandler';
	                $('#fileupload').fileupload({
	                    url: url,
	                    dataType: 'json',
	                    done: function (e, data) {
	                	
	                    },
	                    progressall: function (e, data) {
	                    		                console.log(url);
	                    },
	                    submit: function(e, data) {
	                    		                console.log(url);
	                    }
	                });
	            });
			});

        ";
        OW::getDocument()->addOnloadScript($script);

		$cmpCategory = new SPDOWNLOAD_CMP_Category();
		$this->addComponent('cmpCategory', $cmpCategory);

		$form = new spdownloadForm();
		$form->setEnctype(Form::ENCTYPE_MULTYPART_FORMDATA);

		$this->addForm($form);
		$check = false;
		$this->assign("check", $check);

	}

	public function uploadFile() 
    {
        $uploadPath = OW_DIR_PLUGIN_USERFILES . 'spdownload' . DS;
        @mkdir($uploadPath, 0777);
        $upload_handler = new SPDOWNLOAD_CLASS_UploadHandler(array(
            'upload_dir' => $uploadPath,
            'max_file_size' => '500000000'
        ));
        var_dump($upload_handler);die();
    }

	
}

class spdownloadForm extends Form
{
	public function __construct()
	{
		parent::__construct("form_upload");
		$language = OW::getLanguage();
		
		$nameField = new TextField("upname");
		$nameField->setRequired(true);
		$this->addElement($nameField->setLabel($language->text("spdownload", "form_up_label_name")));

		$slugField = new TextField("upslug");
		$slugField->setRequired(true);
		$this->addElement($slugField->setLabel($language->text("spdownload", "form_up_label_slug")));

		$descField = new WysiwygTextarea("updescription");
		$this->addElement($descField->setLabel($language->text("spdownload", "form_up_label_des")));

		$licenseField = new WysiwygTextarea("uplicense");
		$this->addElement($licenseField->setLabel($language->text("spdownload", "form_up_label_license")));

		$submit = new Submit("upload");
		$submit->setValue($language->text("spdownload", "form_up_label_submit"));
		$this->addElement($submit);
	}
}