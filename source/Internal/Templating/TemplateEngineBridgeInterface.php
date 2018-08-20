<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Templating;

use Symfony\Component\Templating\EngineInterface;

interface TemplateEngineBridgeInterface
{
    public function exists($name);

    public function getEngine();

    public function renderTemplate($templateName, $viewData, $cacheId = null);
}