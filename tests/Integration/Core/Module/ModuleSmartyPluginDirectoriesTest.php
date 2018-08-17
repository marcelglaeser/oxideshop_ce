<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Integration\Core\Module;

use OxidEsales\EshopCommunity\Internal\Templating\TemplateEngineBridge;
use OxidEsales\EshopCommunity\Tests\Integration\Modules\Environment;
use OxidEsales\Eshop\Core\UtilsView;
use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Class ModuleSmartyPluginDirectoryTest
 */
class ModuleSmartyPluginDirectoriesTest extends UnitTestCase
{
    /**
     * Smarty should know about the smarty plugin directories of the modules being activated.
     */
    public function testModuleSmartyPluginDirectoryIsIncludedOnModuleActivation()
    {
        $this->activateTestModule();

        $templating = $this->getContainer()->get(TemplateEngineBridge::class);

        $this->assertTrue(
            $this->isPathInSmartyDirectories($templating, 'Smarty/PluginDirectory1WithMetadataVersion21')
        );

        $this->assertTrue(
            $this->isPathInSmartyDirectories($templating, 'Smarty/PluginDirectory2WithMetadataVersion21')
        );
    }

    public function testSmartyPluginDirectoriesOrder()
    {
        $this->activateTestModule();

        $templating = $this->getContainer()->get(TemplateEngineBridge::class);

        $this->assertModuleSmartyPluginDirectoriesFirst($templating->getEngine()->plugins_dir);
        $this->assertShopSmartyPluginDirectorySecond($templating->getEngine()->plugins_dir);
    }

    /**
     * @internal
     *
     * @return \Psr\Container\ContainerInterface
     */
    private function getContainer()
    {
        \OxidEsales\EshopCommunity\Internal\Application\ContainerFactory::getInstance()->resetContainer();
        return \OxidEsales\EshopCommunity\Internal\Application\ContainerFactory::getInstance()->getContainer();
    }

    private function assertModuleSmartyPluginDirectoriesFirst($directories)
    {
        $this->assertContains(
            'Smarty/PluginDirectory1WithMetadataVersion21',
            $directories[0]
        );

        $this->assertContains(
            'Smarty/PluginDirectory2WithMetadataVersion21',
            $directories[1]
        );
    }

    private function assertShopSmartyPluginDirectorySecond($directories)
    {
        $this->assertContains(
            'Core/Smarty/Plugin',
            $directories[2]
        );
    }

    private function isPathInSmartyDirectories($smarty, $path)
    {
        foreach ($smarty->getEngine()->plugins_dir as $directory) {
            if (strpos($directory, $path)) {
                return true;
            }
        }

        return false;
    }

    private function activateTestModule()
    {
        $modules = ['with_metadata_v21'];
        $environment = new Environment();
        $environment->prepare($modules);
        $environment->activateModules($modules);
    }
}
