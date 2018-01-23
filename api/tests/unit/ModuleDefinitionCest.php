<?php

namespace api\tests\unit;

use api\tests\UnitTester;

class ModuleDefinitionCest
{
    public function tryToGetModuleVersion(UnitTester $I)
    {
        $module = new \api\v1\Module('api');
        $I->assertTrue(version_compare($module->getVersion(), '1.0', '>='));
        $I->assertTrue(version_compare($module->getVersion(), '2.0', '<'));
    }
}