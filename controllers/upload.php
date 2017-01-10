<?php 

class SPDOWNLOAD_CTRL_Upload extends OW_ActionController
{
	public function index()
	{
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
			var r = new Resumable({
			  target:'$urlUploadHandler', 
			  simultaneousUploads:1,
			  maxFiles:1,
			});
			 
			r.assignBrowse(document.getElementById('fileupload'));

			r.on('fileSuccess', function(file){
			    var str = '<tr><td align=\"center\" ><input class=\"buttonradio\" name=\"main\" value=\"'+ file.fileName +'\" type=\"radio\"></td><td align=\"center\" >'+ file.fileName +'</td><td align=\"center\" ><a class=\"delete_file\"><div class=\"ow_ic_delete\"></div></a><input type=\"hidden\" name=\"filesPost[]\" value=\"'+file.fileName+'!^0^!'+file.size+'!^0^!Add'+'\" ></td></tr>'
			    $('#Listfile tbody').append( str );
			    $('#file_btn_browse').removeClass('ow_disable');
			    $('#file_btn_browse').removeAttr('disabled');
			  });
			r.on('fileAdded', function(file, event){
			    r.upload();
			    var str = '<tr id=\"file_progress\"><td colspan=\"2\"><div class=\"sp-progress-container sp-round-xlarge\"><div class=\"sp-progressbar sp-round-xlarge\" ></div></div></td><td align=\"center\" ><a class=\"deleteFileProgress\"><div class=\"ow_ic_delete\"></div></a></td></tr>';
			    $('#Listfile tbody').append( str );
			    $('#file_btn_browse').addClass('ow_disable');
			    $('#file_btn_browse').attr('disabled','disabled');
			    total = file.chunks.length;
			  });
			r.on('filesAdded', function(array){
				console.log(array[0]);
				console.log(array[1]);
			  });
			r.on('complete', function(){
				$('#file_progress').remove();
			  });
			r.on('progress', function(){
				var width = r.progress()*100;
			    $('.sp-progressbar').css('width', width + \"%\");
			    console.log(width);
			  });
			r.on('error', function(message, file){
				r.error();
				$('#file_progress').remove();
			  });
			r.on('cancel', function(){
				$('#file_progress').remove();
				$('#file_btn_browse').removeClass('ow_disable');
			    $('#file_btn_browse').removeAttr('disabled');
			  });

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
			

			var t = new Resumable({
			  target:'$urlUploadHandler', 
			  simultaneousUploads:1,
			});  
			t.assignBrowse(document.getElementById('thumbsUpload'));

			t.on('fileSuccess', function(file){
				var imgThumb = pathTemp + file.fileName;
				$('.'+file.uniqueIdentifier).attr('src', imgThumb);
				var str1 = '<input class=\"'+file.uniqueIdentifier+'\" type=\"hidden\" name=\"thumbs[]\" value=\"'+file.fileName+'!^0^!'+file.size+'!^0^!Add\" />';
				$('#listthumb').append(str1);

			  });
			t.on('fileAdded', function(file, event){
				var str = '<div class=\"divThumb ow_left\"><img width=\"100%\" class=\"'+file.uniqueIdentifier+'\" src=\"$loadimage\" /></div>';
				$('#listthumb').append(str);
				t.upload();
			  });
			t.on('fileRetry', function(file){
			  });
			t.on('fileError', function(file, message){
				console.log(file);
			  });
			t.on('complete', function(){
			  });
			t.on('progress', function(){
			  });


		  	$(document).on('click', '.deleteFileProgress', function() {
		  		r.cancel();
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

		if ( OW::getRequest()->isPost() ) {
			var_dump($_POST);die();
		}


	}

	public function uploadFile() 
    {
        $uploadPath = OW_DIR_PLUGIN_USERFILES . 'spdownload' . DS;
        @mkdir($uploadPath, 0777);
        $upload_handler = new SPDOWNLOAD_CLASS_UploadHandler(array(
            'upload_dir' => $uploadPath,
            'max_file_size' => '500000000'
        ));
    }

	public function resumable()
	{
		$plugin = OW::getPluginManager()->getPlugin('spdownload');
        $path = $plugin->getUserFilesDir();
        $userId = OW::getUser()->getId();

		if ($_SERVER['REQUEST_METHOD'] === 'GET') {

		    if(!(isset($_GET['resumableIdentifier']) && trim($_GET['resumableIdentifier'])!='')){
		        $_GET['resumableIdentifier']='';
		    }
		    $temp_dir = $_GET['resumableIdentifier'];
		    if(!(isset($_GET['resumableFilename']) && trim($_GET['resumableFilename'])!='')){
		        $_GET['resumableFilename']='';
		    }
		    if(!(isset($_GET['resumableChunkNumber']) && trim($_GET['resumableChunkNumber'])!='')){
		        $_GET['resumableChunkNumber']='';
		    }
		    $chunk_file = $temp_dir.'/'.$_GET['resumableFilename'].'.part'.$_GET['resumableChunkNumber'];
		    var_dump($chunk_file);
		    if (file_exists($chunk_file)) {
		         header("HTTP/1.0 200 Ok");
		       } else {
		         header("HTTP/1.0 404 Not Found");
		       }
		}

		// loop through files and move the chunks to a temporarily created directory
		if (!empty($_FILES)) foreach ($_FILES as $file) {

		    // check the error status
		    if ($file['error'] != 0) {
		        $this->_log('error '.$file['error'].' in file '.$_POST['resumableFilename']);
		        continue;
		    }

		    // init the destination file (format <filename.ext>.part<#chunk>
		    // the file is stored in a temporary directory
		    if(isset($_POST['resumableIdentifier']) && trim($_POST['resumableIdentifier'])!=''){
		        $temp_dir = $path.'temp/'.$userId.'/'.$_POST['resumableIdentifier'];
		    }
		    $dest_file = $temp_dir.'/'.$_POST['resumableFilename'].'.part'.$_POST['resumableChunkNumber'];

		    // create the temporary directory
		    if (!is_dir($temp_dir)) {
		        mkdir($temp_dir, 0777, true);
		    }

		    // move the temporary file
		    if (!move_uploaded_file($file['tmp_name'], $dest_file)) {
		        $this->_log('Error saving (move_uploaded_file) chunk '.$_POST['resumableChunkNumber'].' for file '.$_POST['resumableFilename']);
		    } else {
		        // check if all the parts present, and create the final destination file
		        $this->createFileFromChunks($temp_dir, $_POST['resumableFilename'],$_POST['resumableChunkSize'], $_POST['resumableTotalSize'],$_POST['resumableTotalChunks']);
		    }
		}
	}

	public function _log($str) 
	{

	    // log to the output
	    $log_str = date('d.m.Y').": {$str}\r\n";
	    echo $log_str;

	    // log to file
	    if (($fp = fopen('upload_log.txt', 'a+')) !== false) {
	        fputs($fp, $log_str);
	        fclose($fp);
	    }
	}

	public function rrmdir($dir) 
	{
	    if (is_dir($dir)) {
	        $objects = scandir($dir);
	        foreach ($objects as $object) {
	            if ($object != "." && $object != "..") {
	                if (filetype($dir . "/" . $object) == "dir") {
	                    $this->rrmdir($dir . "/" . $object); 
	                } else {
	                    unlink($dir . "/" . $object);
	                }
	            }
	        }
	        reset($objects);
	        rmdir($dir);
	    }
	}

	public function createFileFromChunks($temp_dir, $fileName, $chunkSize, $totalSize,$total_files) 
	{
		$plugin = OW::getPluginManager()->getPlugin('spdownload');
        $path = $plugin->getUserFilesDir();
        $userId = OW::getUser()->getId();

	    // count all the parts of this file
	    $total_files_on_server_size = 0;
	    $temp_total = 0;
	    foreach(scandir($temp_dir) as $file) {
	        $temp_total = $total_files_on_server_size;
	        $tempfilesize = filesize($temp_dir.'/'.$file);
	        $total_files_on_server_size = $temp_total + $tempfilesize;
	    }
	    // check that all the parts are present
	    // If the Size of all the chunks on the server is equal to the size of the file uploaded.
	    if ($total_files_on_server_size >= $totalSize) {
	    // create the final destination file 
	        if (($fp = fopen($path.'temp/'.$userId.'/'.$fileName, 'w')) !== false) {
	            for ($i=1; $i<=$total_files; $i++) {
	                fwrite($fp, file_get_contents($temp_dir.'/'.$fileName.'.part'.$i));
	                $this->_log('writing chunk '.$i);
	            }
	            fclose($fp);
	        } else {
	            $this->_log('cannot create the destination file');
	            return false;
	        }

	        // rename the temporary directory (to avoid access from other 
	        // concurrent chunks uploads) and than delete it
	        if (rename($temp_dir, $temp_dir.'_UNUSED')) {
	            $this->rrmdir($temp_dir.'_UNUSED');
	        } else {
	            $this->rrmdir($temp_dir);
	        }
	    }

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