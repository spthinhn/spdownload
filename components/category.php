<?php
class SPDOWNLOAD_CMP_Category extends OW_Component
{
	public function __construct()
	{
		parent::__construct();
		$categories = $this->listcategory();
		$this->assign("categories", $categories);
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
}