<?php
/**
 * Created by PhpStorm.
 * User: vilma
 * Date: 06.08.18
 * Time: 13:43
 */

namespace OxidEsales\EshopCommunity\Internal\Smarty;


/**
 * Stores the Smarty configuration.
 */
interface SmartyFactoryInterface
{
    /**
     * @return \Smarty
     */
    public function getSmarty();
}