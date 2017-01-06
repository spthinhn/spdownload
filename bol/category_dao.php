<?php

class SPDOWNLOAD_BOL_CategoryDao extends OW_BaseDao
{
    protected function __construct()
    {
        parent::__construct();
    }

    private static $classInstance;

    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    public function getDtoClassName()
    {
        return 'SPDOWNLOAD_BOL_Category';
    }

    public function getTableName()
    {
        return OW_DB_PREFIX . 'spdownload_categories';
    }

    public function getCategoryListByParent($parent)
    {
    	$example = new OW_Example();
        $example->andFieldEqual('parent', $parent);

        return $this->findListByExample($example);
    }

    public function getCategoryListNotInId( $idNotIn )
    {
        $select = '*';
        $from   = $this->getTableName();
        $where  = '`id` NOT IN ('.$idNotIn.')';
        $query  = "SELECT $select FROM $from WHERE $where ";

        return $this->dbo->queryForObjectList($query, $this->getDtoClassName());
    }

}