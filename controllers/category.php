<?php 

class SPDOWNLOAD_CTRL_Category extends OW_ActionController
{
	public function index($requests = null)
	{
		$this->setPageTitle(OW::getLanguage()->text("spdownload", "title_category_add"));
		$this->setPageHeading(OW::getLanguage()->text("spdownload", "head_category_add"));

		OW::getDocument()->addStyleSheet(OW::getPluginManager()->getPlugin('spdownload')->getStaticCssUrl() . 'style.css');

		OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('spdownload')->getStaticJsUrl() . 'custom.js');

		$check = false;
		if (!empty($requests)) {
			$check = $this->checkRequest($requests);
			if (!$check) {
				$this->redirect(OW::getRouter()->urlForRoute('spdownload.category_index'));
			}
		}
		$this->assign("check", $check);
		if (isset($requests["id"])) {
			$form = new spdownloadForm($requests["id"]);
		} else {
			$form = new spdownloadForm();
		}
		
		if ($check) {
			
			$var = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryById($requests["id"]);
			$form->setValues(array(
				"nameCategory" => $var->name,
				"parentCategory" => $var->parent
			));	
		}
		$this->addForm($form);


		$categories = $this->listcategory();
		$this->assign("categories", $categories);

		$cmpCategory = new SPDOWNLOAD_CMP_Category();
		$this->addComponent('cmpCategory', $cmpCategory);
		
		if ( OW::getRequest()->isPost() )
        {
            if ( $form->isValid($_POST) )
            {
            	$data = array();
            	$data["id"] = null;
            	if ($check) {
					$var = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryById($requests["id"]);
					$data["id"] = $var->id;
				} 
            	$data["name"] = $_POST["nameCategory"];
            	if (!isset($_POST["parentCategory"]) || empty($_POST["parentCategory"]) ) {
            		$_POST["parentCategory"] = 0;
            	}
            	$data["parent"] = $_POST["parentCategory"];

            	$this->addCategory($data);

            	$this->redirect(OW::getRouter()->urlForRoute('spdownload.category_index'));
            }
        }
	}

	public function addCategory($data)
	{
		if ( OW::getRequest()->isPost() )
        {
        	SPDOWNLOAD_BOL_CategoryService::getInstance()->addCategory($data);
        }
	}

	public function updateCategory($requests)
	{

	}

	private function listcategory($parent=0, $level=0, $listArr=array())
	{
		$list = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryListByParent($parent);
		foreach ($list as $keyList => $valList) {
			$valList->level = $level;
			array_push($listArr, $valList);
			$listArr = $this->listCategory($valList->id, $level+1, $listArr);
		}
		return $listArr;
	}

	private function checkRequest($requests)
	{
		$flag = false;
		if (isset($requests["id"]) && isset($requests["name"])) {
			$id = $requests["id"];
			$name = $requests["name"];
			$var = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryById($id);
			if ($var->name == $name) {
				$flag = true;
			}
		}
		return $flag;
	}
}

class spdownloadForm extends Form
{
	public function __construct($idNotIn = null)
	{
		parent::__construct("form_category_add");
		$language = OW::getLanguage();
		if ($idNotIn) {
			$listCategory = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryListNotInId($idNotIn);
		} else {
			$listCategory = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryList();
		}

		$nameField = new TextField("nameCategory");
		$nameField->setRequired(true);
		$this->addElement($nameField->setLabel($language->text("spdownload", "form_cate_label_name")));

		$parentField = new Selectbox("parentCategory");
		foreach ($listCategory as $keyCategory => $valCategory) {
			$parentField->addOption($valCategory->id, $valCategory->name);
		}
		$this->addElement($parentField->setLabel($language->text("spdownload", "form_cate_label_parent")));

		$submit = new Submit("form_category_add");
		$submit->setValue($language->text("spdownload", "form_cate_label_submit"));
		$this->addElement($submit);
	}
}