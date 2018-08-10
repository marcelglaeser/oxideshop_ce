<?php
/**
 * Created by PhpStorm.
 * User: vilma
 * Date: 06.08.18
 * Time: 16:02
 */

namespace OxidEsales\EshopCommunity\Internal\Smarty;


interface TemplateEngineConfigurationInterface
{
    /**
     * @return array
     */
    public function getParameters();
}