<?php 

class SPDOWNLOAD_CTRL_Action extends OW_ActionController
{
	public function explodeParams($data)
	{
		$arr = explode("-", $data["params"]);
		$data["id"] = $arr[0];

		return $data;
	}

	public function checkRequest($data, $table)
	{
		$data = $this->explodeParams($data);
		$flag = false;
		if (isset($data["id"])) {
			switch ($table) {
				case 'category':
					$object = SPDOWNLOAD_BOL_CategoryService::getInstance()->getCategoryById($data["id"]);
					break;
				case 'platform':
					$object = SPDOWNLOAD_BOL_PlatformService::getInstance()->getPlatformById($data["id"]);
					break;
				default:
					$flag = false;
					break;
			}
			$urlTemp = $object->id.'-'.$object->slug;
			if ($urlTemp == urldecode($data["params"])) {
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