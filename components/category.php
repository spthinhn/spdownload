<?php
class SPDOWNLOAD_CMP_Category extends OW_Component
{
	public function __construct()
	{
		parent::__construct();
		$categories = $this->listCategory();
		$actual_link = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$url = OW::getRouter()->urlForRoute('spdownload.upload_index');
		$checkCate = false;
		if ($actual_link == $url) {
			$checkCate = true;
		}
		$this->assign("categories", $categories);
		$this->assign("checkCate", $checkCate);
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