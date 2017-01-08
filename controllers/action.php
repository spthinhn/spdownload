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
}