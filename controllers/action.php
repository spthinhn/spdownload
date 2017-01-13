<?php 

class SPDOWNLOAD_CTRL_Action extends OW_ActionController
{
	public function convertParamsToArray($data)
	{
		$arr = explode("-", $data["params"]);
		$data["id"] = $arr[0];
		$data["name"] = $arr[1];

		return $data;
	}

	public function checkRequestCategory($data)
	{
		$params = $this->convertParamsToArray($data);
		$flag = false;
		if (isset($params["id"]) && isset($params["name"])) {
			$id = $params["id"];
			$name = $params["name"];
			$var = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryById($id);
			if ($var->name == $name) {
				$flag = true;
			}
		}
		return $flag;
	}

	public function convertStringImageToArray($stringImage)
	{
		$arr = explode("!^0^!", $stringImage);
		$data["nameImage"] = $arr[0];
		$data["sizeImage"] = $arr[1];
		$data["actionImage"] = $arr[2];

		return $data;
	}

	public function copyFile($data)
	{
		if (!is_dir($data["to"])) {
			mkdir($data["to"]);
		}
		copy($data["from"].$data["name"], $data["to"].$data["name"]);
	}

	public function setPathFile()
	{
		$document = OW::getDocument();
		$plugin = OW::getPluginManager()->getPlugin('spdownload');
		$urlUserFiles = $plugin->getUserFilesUrl();
		$dirUserFiles = $plugin->getUserFilesDir();

		$path["url"]["platform"] = $urlUserFiles.'platform/';
		$path["url"]["temp"] = $urlUserFiles.'temp/';

		$path["dir"]["platform"] = $dirUserFiles.'platform/';
		$path["dir"]["temp"] = $dirUserFiles.'temp/';

	}
}