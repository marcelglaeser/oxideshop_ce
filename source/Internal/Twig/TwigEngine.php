<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 *
 * @author Jędrzej Skoczek & Tomasz Kowalewski
 */

namespace OxidEsales\EshopCommunity\Internal\Twig;

use OxidEsales\EshopCommunity\Internal\Templating\BaseEngineInterface;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;

class TwigEngine implements BaseEngineInterface
{
    /**
     * @var \Twig_Environment
     */
    private $engine;

    /**
     * @var TemplateNameParserInterface
     */
    protected $parser;

	/**
	 * Array of global parameters
	 *
	 * @var array
	 */
	private $globals = [];


    /**
     * TwigEngine constructor.
     * @param TemplateEngineConfigurationInterface $engine
     * @param TemplateNameParserInterface $parser
     */
    public function __construct(TemplateEngineConfigurationInterface $configuration, TemplateNameParserInterface $parser)
    {

    	$properties = $configuration->getParameters();
		$directories = array_filter($properties['template_dir'], function ($directory) {
			return is_dir($directory);
		});

		$loader = new \Twig_Loader_Filesystem($directories);
		$this->engine = new \Twig_Environment($loader);
        $this->parser = $parser;
    }

    /**
     * Renders a template.
     *
     * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
     * @param array $parameters An array of parameters to pass to the template
     *
     * @return string The evaluated template as a string
     *
     * @throws \Throwable
	 * @throws \Twig_Error_Loader
	 * @throws \Twig_Error_Runtime
	 * @throws \Twig_Error_Syntax
	 */
    public function render($name, array $parameters = array())
    {
        return $this->engine->render($name, $parameters);
    }

    /**
     * Returns true if the template exists.
     *
     * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
     *
     * @return bool true if the template exists, false otherwise
     *
     * @throws \RuntimeException if the engine cannot handle the template name
     */
    public function exists($name)
    {
        return $this->engine->getLoader()->exists($name);
    }

    /**
     * Returns true if this class is able to render the given template.
     *
     * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
     *
     * @return bool true if this class supports the given template, false otherwise
     */
    public function supports($name)
    {
        $template = $this->parser->parse($name);

        return 'twig' === $template->get('engine');
    }

    public function setCacheId($cacheId)
    {
    }

    public function addGlobal($name, $value)
	{
		$this->globals[$name] = $value;
	}

	public function getGlobals()
	{
		return $this->globals;
	}

}