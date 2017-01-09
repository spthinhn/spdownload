<?php 

class SPDOWNLOAD_CTRL_Action extends OW_ActionController
{
	public function check($requests = null)
	{
		$arr = explode("-", $requests["params"]);
		$requests["id"] = $arr[0];
		$requests["name"] = $arr[1];

		return $requests;
	}

	public function checkRequestCategory($requests)
	{
		$requests = $this->check($requests);
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