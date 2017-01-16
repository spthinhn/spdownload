<?php 

class SPDOWNLOAD_CTRL_Category extends OW_ActionController
{
	public function index($requests = null)
	{
		$this->setPageTitle(OW::getLanguage()->text("spdownload", "titleCategory"));
		$this->setPageHeading(OW::getLanguage()->text("spdownload", "headCategory"));

		OW::getDocument()->addStyleSheet(OW::getPluginManager()->getPlugin('spdownload')->getStaticCssUrl() . 'style.css');

		OW::getDocument()->addScript(OW::getPluginManager()->getPlugin('spdownload')->getStaticJsUrl() . 'custom.js');

		$this->assign("urlAddCategory", OW::getRouter()->urlForRoute('spdownload.category_add'));

		$categories = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryList();
		foreach ($categories as $key => $value) {
			if ($value->parent == 0) {
				$value->parent = "";
			} else {
				$category = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryById($value->parent);
				$value->parent = $category->name;
			}
			$tempSlug = $value->id."-".$value->slug;
			$value->edit = OW::getRouter()->urlForRoute('spdownload.category_edit', array('params'=> $tempSlug));
		}
		$this->assign("categories", $categories);

		
	}

	public function add()
	{
		$this->setPageTitle(OW::getLanguage()->text("spdownload", "titleCategoryAdd"));
		$this->setPageHeading(OW::getLanguage()->text("spdownload", "headCategoryAdd"));

		$form = new spdownloadForm("formCategoryAdd");
		$this->addForm($form);

		if (OW::getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
            	$data["id"] = null;
            	$data["slug"] = $_POST["slugCategory"];
            	$data["name"] = $_POST["nameCategory"];
            	if (!isset($_POST["parentCategory"]) || empty($_POST["parentCategory"])) {
            		$_POST["parentCategory"] = 0;
            	}
            	$data["parent"] = $_POST["parentCategory"];
            	SPDOWNLOAD_BOL_CategoryService::getInstance()->addCategory($data);
            	$this->redirect(OW::getRouter()->urlForRoute('spdownload.category_index'));
            }
        }
	}

	public function edit($data)
	{
		$this->setPageTitle(OW::getLanguage()->text("spdownload", "titleCategoryEdit"));
		$this->setPageHeading(OW::getLanguage()->text("spdownload", "headCategoryEdit"));

		$action = new SPDOWNLOAD_CTRL_Action();
		$data = $action->explodeParams($data);
		$flag = $action->checkRequest($data, "category");

		if (!$flag) {
			$this->redirect(OW::getRouter()->urlForRoute('spdownload.category_index'));
		}

		$form = new spdownloadForm("formCategoryEdit", $data["id"]);
		$getValCategory = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryById($data["id"]);
		$form->setValues(array(
			"nameCategory" => $getValCategory->name,
			"slugCategory" => $getValCategory->slug,
			"parentCategory" => $getValCategory->parent
		));
		$this->addForm($form);

		$tempSlug = $getValCategory->id."-".$getValCategory->slug;
		$this->assign("urlDeleteCategory", OW::getRouter()->urlForRoute('spdownload.category_delete', array('params'=> $tempSlug)));
		$this->assign("urlCancelCategory", OW::getRouter()->urlForRoute('spdownload.category_index'));
		
		if (OW::getRequest()->isPost()) {
        	$data["slug"] = $_POST["slugCategory"];
        	$data["name"] = $_POST["nameCategory"];
        	if (!isset($_POST["parentCategory"]) || empty($_POST["parentCategory"])) {
        		$_POST["parentCategory"] = 0;
        	}
        	$data["parent"] = $_POST["parentCategory"];
        	SPDOWNLOAD_BOL_CategoryService::getInstance()->addCategory($data);
        	$this->redirect(OW::getRouter()->urlForRoute('spdownload.category_index'));
        }
	}

	public function delete($data)
	{
		$action = new SPDOWNLOAD_CTRL_Action();
		$data = $action->explodeParams($data);
		$flag = $action->checkRequest($data, "category");
		if (!$flag) {
			$this->redirect(OW::getRouter()->urlForRoute('spdownload.category_index'));
		}

    	SPDOWNLOAD_BOL_CategoryService::getInstance()->deleteCategory($data);
    	$this->redirect(OW::getRouter()->urlForRoute('spdownload.category_index'));
	}

	private function listCategory($parent=0, $level=0, $listArr=array())
	{
		$list = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryListByParent($parent);
		foreach ($list as $keyList => $valList) {
			$valList->level = $level;
			array_push($listArr, $valList);
			$listArr = $this->listCategory($valList->id, $level+1, $listArr);
		}
		return $listArr;
	}

	
}

class spdownloadForm extends Form
{
	public function __construct($nameForm, $idNotIn = null)
	{
		parent::__construct($nameForm);
		$language = OW::getLanguage();
		if ($idNotIn) {
			$listCategory = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryListNotInId($idNotIn);
		} else {
			$listCategory = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryList();
		}

		$nameField = new TextField("nameCategory");
		$nameField->setRequired(true);
		$this->addElement($nameField->setLabel($language->text("spdownload", "formCategoryLabelName")));

		$slugField = new TextField("slugCategory");
		$slugField->setRequired(true);
		$this->addElement($slugField->setLabel($language->text("spdownload", "formCategoryLabelSlug")));

		$parentField = new Selectbox("parentCategory");
		foreach ($listCategory as $keyCategory => $valCategory) {
			$parentField->addOption($valCategory->id, $valCategory->name);
		}
		$this->addElement($parentField->setLabel($language->text("spdownload", "formCategoryLabelParent")));

		$submit = new Submit($nameForm);
		$submit->setValue($language->text("spdownload", "formCategoryLabelSubmit"));
		$this->addElement($submit);
	}
}