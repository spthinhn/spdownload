<?php

class SPDOWNLOAD_BOL_PlatformDao extends OW_BaseDao
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
        return 'SPDOWNLOAD_BOL_Platform';
    }

    public function getTableName()
    {
        return OW_DB_PREFIX . 'spdownload_platforms';
    }

}