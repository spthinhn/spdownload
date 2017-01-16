<?php

class SPDOWNLOAD_BOL_CategoryService
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

    public function addCategory($data)
    {
    	$category = new SPDOWNLOAD_BOL_Category();
        if (!isset($data["id"])) {
            $data["id"] = null;
        }
        $category->id = $data["id"];
        $category->name = $data["name"];
        $category->slug = $data["slug"];
        $category->parent = $data["parent"];
        SPDOWNLOAD_BOL_CategoryDao::getInstance()->save($category);

        if (isset($data["id"])) {
            BOL_LanguageService::getInstance()->replaceLangValue('spdownload',$this->getCategoryKey($category->id),trim($category->name));
        } else {
            BOL_LanguageService::getInstance()->addValue(
                OW::getLanguage()->getCurrentId(),
                'spdownload',
                $this->getCategoryKey($category->id),
                trim($category->name));
        }
    }

    public function deleteCategory($data)
    {
        $category = new SPDOWNLOAD_BOL_Category();
        $category->id = $data["id"];
        SPDOWNLOAD_BOL_CategoryDao::getInstance()->delete($category);
    }

    private function getCategoryKey( $name )
    {
        return 'category_' . trim($name);
    }

    public function getCategoryList()
    {
        return SPDOWNLOAD_BOL_CategoryDao::getInstance()->findAll();
    }

    public function getCategoryListByParent($parent)
    {
    	return SPDOWNLOAD_BOL_CategoryDao::getInstance()->getCategoryListByParent($parent);
    }

    public function getCategoryById($id)
    {
    	return SPDOWNLOAD_BOL_CategoryDao::getInstance()->findById($id);	
    }

    public function getCategoryListNotInId( $idNotIn )
    {
        return SPDOWNLOAD_BOL_CategoryDao::getInstance()->getCategoryListNotInId($idNotIn);
    }

}