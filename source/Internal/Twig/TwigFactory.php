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
class TwigFactory implements TwigFactoryInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var array
     */
    private $properties;

    /**
     * TwigEnvironment constructor.
     *
     * @param TemplateEngineConfigurationInterface $configuration
     */
    public function __construct(TemplateEngineConfigurationInterface $configuration)
    {
        $this->properties = $configuration->getParameters();
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        $directories = array_filter($this->properties['template_dir'], function ($directory) {
            return is_dir($directory);
        });

        $loader = new \Twig_Loader_Filesystem($directories);
        $this->twig = new \Twig_Environment($loader);

        return $this->twig;
    }
}