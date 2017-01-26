<?php
namespace common\tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Functional extends \Codeception\Module
{
    public function _afterSuite()
    {
        $module = $this->getModule('\bscheshirwork\Codeception\Module\DbYii2Config');
        $module->_initialize();
        $this->dbh = null;
        $this->driver = null;
        parent::_afterSuite();
    }
}