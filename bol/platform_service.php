<?php

class SPDOWNLOAD_BOL_PlatformService
{
	private static $classInstance;

    /**
     * Returns an instance of class (singleton pattern implementation).
     *
     * @return CONTACTUS_BOL_Service
     */
    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private function __construct()
    {

    }

    public function addPlatform($data)
    {
        $platform = new SPDOWNLOAD_BOL_Platform();
        $platform->id = $data["id"];
        $platform->name = $data["name"];
        $platform->thumb = $data["thumb"];
        SPDOWNLOAD_BOL_PlatformDao::getInstance()->save($platform);

        if (isset($data["id"])) {
            BOL_LanguageService::getInstance()->replaceLangValue('spdownload',$this->getPlatformKey($platform->id),trim($platform->name));
        } else {
            BOL_LanguageService::getInstance()->addValue(
                OW::getLanguage()->getCurrentId(),
                'spdownload',
                $this->getPlatformKey($platform->id),
                trim($platform->name));
        }
    }

    public function deletePlatform($data)
    {
        $platform = new SPDOWNLOAD_BOL_Platform();
        $platform->id = $data["id"];
        SPDOWNLOAD_BOL_PlatformDao::getInstance()->delete($platform);
    }

    private function getPlatformKey( $name )
    {
        return 'platform_' . trim($name);
    }

    public function getPlatformList()
    {
        return SPDOWNLOAD_BOL_PlatformDao::getInstance()->findAll();
    }

    public function getPlatformById($id)
    {
    	return SPDOWNLOAD_BOL_PlatformDao::getInstance()->findById($id);	
    }

}