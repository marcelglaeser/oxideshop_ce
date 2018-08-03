<?php
/**
 * Created by PhpStorm.
 * User: vilma
 * Date: 02.08.18
 * Time: 13:38
 */

namespace OxidEsales\EshopCommunity\Core\Templating;


use OxidEsales\Eshop\Core\UtilsView;
use Symfony\Component\Templating\TemplateNameParser;

class TemplateRenderer
{
    public function renderTemplate($templateName, $viewData, $view = null)
    {
        $templateNameParser = new TemplateNameParser();
        $cacheId = null;

        // get Smarty is important here as it sets template directory correct
        $utils = new UtilsView();
        $smarty = $utils->getSmarty(true);
        if ($view) {
            $cacheId = $view->getViewId();
        }
        $templating = new SmartyEngine($smarty, $templateNameParser);
        $templating->setCacheId($cacheId);

        return $templating->render($templateName, $viewData);
    }
}