<?php 

class SPDOWNLOAD_CTRL_Upload extends OW_ActionController
{
	public function index()
	{
		OW::getDocument()->addStyleSheet(OW::getPluginManager()->getPlugin('spdownload')->getStaticCssUrl() . 'style.css');
		
		$cmpCategory = new SPDOWNLOAD_CMP_Category();
		$this->addComponent('cmpCategory', $cmpCategory);

		$form = new spdownloadForm();
		$this->addForm($form);

		$check = false;
		$this->assign("check", $check);
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