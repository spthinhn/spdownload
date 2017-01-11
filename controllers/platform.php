<?php 

class SPDOWNLOAD_CTRL_Platform extends OW_ActionController
{
	public function index()
	{
		$this->setPageTitle(OW::getLanguage()->text("spdownload", "titlePlatformAdd"));
		$this->setPageHeading(OW::getLanguage()->text("spdownload", "headPlatformAdd"));
	}

	public function add()
	{
		$this->setPageTitle(OW::getLanguage()->text("spdownload", "titlePlatformAdd"));
		$this->setPageHeading(OW::getLanguage()->text("spdownload", "headPlatformAdd"));

		$document = OW::getDocument();
		$plugin = OW::getPluginManager()->getPlugin('spdownload');

		/* Begin CSS */
		$document->addStyleSheet($plugin->getStaticCssUrl() . 'style.css');
		$document->addStyleSheet($plugin->getStaticCssUrl() . 'custom.css');
		/* End CSS */

		/* Begin JS */
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
		$document->addScript($plugin->getStaticJsUrl().'resumable.js');
		/* End JS */

		$pathImgNone = $plugin->getStaticUrl().'img/icon_none.png';
        $this->assign("pathImgNone", $pathImgNone);
		
		$urlUploadHandler = OW::getRouter()->urlForRoute('spdownload.resumable');
        $path = $plugin->getUserFilesUrl();
        $loadImage = $plugin->getStaticUrl().'img/' . 'loading.gif';
        $userId = OW::getUser()->getId();
        $pathTemp = $path.'temp/'.$userId.'/';

        $script = "
        	var pathTemp = '$pathTemp';

			var i = new Resumable({
			  target:'$urlUploadHandler', 
			  simultaneousUploads:1,
			  maxFiles:1,
			});  
			i.assignBrowse(document.getElementById('iconUpload'));

			i.on('fileSuccess', function(file){
				$('.iconHidden').remove();
				var imgIcon = pathTemp + file.fileName;
				$('#imgIcon').attr('src', imgIcon);
				var str = '<input class=\"iconHidden\" type=\"hidden\" name=\"iconPlatform\" value=\"'+file.fileName+'!^0^!'+file.size+'!^0^!Add\" />';
				$('#imgIcon').parent().parent().append(str);
			  });
			i.on('fileAdded', function(file, event){
				i.upload();
			  });
			i.on('fileRetry', function(file){
			  });
			i.on('fileError', function(file, message){
				console.log(file);
			  });
			i.on('complete', function(){
			  });
			i.on('progress', function(){
				$('#imgIcon').attr('src', '$loadImage');
			  });

        ";
        OW::getDocument()->addOnloadScript($script);

		$form = new spdownloadForm();
		$form->setEnctype(Form::ENCTYPE_MULTYPART_FORMDATA);

		$this->addForm($form);

		if ( OW::getRequest()->isPost() ) {

			if ( $form->isValid($_POST) ) {
            	$data = array();
            	$data["id"] = null;
            	$data["name"] = $_POST["upName"];

            	$action = new SPDOWNLOAD_CTRL_Action();
				$arrStringImage = $action->convertStringImageToArray($_POST["iconPlatform"]);

            	if ($arrStringImage["actionImage"] == "Add") {
            		$data["thumb"] = $arrStringImage["nameImage"];
            	} else {
            		$data["thumb"] = $arrStringImage["nameImage"];
            	}

            	$pathTempDir = $plugin->getUserFilesDir();
            	$data["from"] = $pathTempDir.'temp/'.$userId.'/';
            	$data["to"] = $pathTempDir.$userId.'/platform/';
            	$data["name"] = $data["thumb"];
            	$this->moveFile($data);
            	die();

            	$this->redirect(OW::getRouter()->urlForRoute('spdownload.platform_index'));
            }
			
		}

	}

	private function moveFile($data)
	{
		if (!is_dir($data["to"])) {
			mkdir($data["to"]);
		}
		rename($data["from"].$data["name"], $data["to"].$data["name"]);
	}
}


class spdownloadForm extends Form
{
	public function __construct()
	{
		parent::__construct("formUpload");
		$language = OW::getLanguage();
		
		$nameField = new TextField("upName");
		$nameField->setRequired(true);
		$this->addElement($nameField->setLabel($language->text("spdownload", "formUpLabelName")));

		$iconField = new TextField("upIcon");
		$this->addElement($iconField->setLabel($language->text("spdownload", "formUpLabelIcon")));

		$submit = new Submit("upLoad");
		$submit->setValue($language->text("spdownload", "formUpLabelSubmit"));
		$this->addElement($submit);
	}
}