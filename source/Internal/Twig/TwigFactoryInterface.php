<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 *
 * @author Jędrzej Skoczek & Tomasz Kowalewski
 */

namespace OxidEsales\EshopCommunity\Internal\Twig;

/**
 * Creates and configures the Twig object.
 */
interface TwigFactoryInterface
{
    /**
     * @return \Twig_Environment
     */
    public function getTwig();
}