<?php 

class SPDOWNLOAD_CTRL_Flatform extends OW_ActionController
{
	public function index()
	{
		$this->setPageTitle(OW::getLanguage()->text("spdownload", "title_category_add"));
		$this->setPageHeading(OW::getLanguage()->text("spdownload", "head_category_add"));
	}

	public function add()
	{
		$this->setPageTitle(OW::getLanguage()->text("spdownload", "title_category_add"));
		$this->setPageHeading(OW::getLanguage()->text("spdownload", "head_category_add"));

		$document = OW::getDocument();

		$document->addStyleSheet(OW::getPluginManager()->getPlugin('spdownload')->getStaticCssUrl() . 'style.css');
		$document->addStyleSheet(OW::getPluginManager()->getPlugin('spdownload')->getStaticCssUrl() . 'custom.css');
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
		$document->addScript($plugin->getStaticJsUrl().'resumable.js');
		$pathImgNone = $plugin->getStaticUrl().'img/icon_none.png';
        $this->assign("pathImgNone", $pathImgNone);
		
		$urlUploadHandler = OW::getRouter()->urlForRoute('spdownload.resumable');
        $path = $plugin->getUserFilesUrl();
        $loadimage = $plugin->getStaticUrl().'img/' . 'loading.gif';
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
				var imgIcon = pathTemp + file.fileName;
				$('#imgIcon').attr('src', imgIcon);
				var str = '<input type=\"hidden\" name=\"iconPost[]\" value=\"'+file.fileName+'!^0^!'+file.size+'!^0^!Add\" />';
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
				$('#imgIcon').attr('src', '$loadimage');
			  });

        ";
        OW::getDocument()->addOnloadScript($script);

		

	}

}