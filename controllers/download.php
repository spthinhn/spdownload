<?php 

class SPDOWNLOAD_CTRL_Download extends OW_ActionController
{
	public function index()
	{
		OW::getDocument()->addStyleSheet(OW::getPluginManager()->getPlugin('spdownload')->getStaticCssUrl() . 'style.css');
		
		$cmpCategory = new SPDOWNLOAD_CMP_Category();
		$this->addComponent('cmpCategory', $cmpCategory);
	}

	
}