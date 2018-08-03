<?php
/**
 * Created by PhpStorm.
 * User: vilma
 * Date: 02.08.18
 * Time: 09:34
 */

namespace OxidEsales\EshopCommunity\Core\Templating;

use Symfony\Component\Templating\EngineInterface;

class SmartyEngine implements EngineInterface
{
    private $cacheId;

    /**
     * The template engine.
     *
     * @var \Smarty
     */
    private $engine;

    /**
     * Constructor.
     *
     * @param \Smarty $engine
     */
    public function __construct(\Smarty $engine)
    {
        $this->engine = $engine;
    }
    /**
     * Render the template.
     *
     * @param string $file
     * @param array  $vars
     *
     * @return string
     */
    public function render($name, array $parameters = array())
    {
        foreach ($parameters as $key => $value) {
            $this->engine->assign($key, $value);
        }
        if (isset($this->cacheId)) {
            return $this->engine->fetch($name, $this->cacheId);
        }
        return $this->engine->fetch($name);
    }

    public function setCacheId($cacheId)
    {
        $this->cacheId = $cacheId;
    }

    /**
     * Returns true if this class is able to render the given template.
     *
     * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
     *
     * @return bool    true if this class supports the given template, false otherwise
     *
     * @api
     */
    public function supports($name)
    {
        //Todo
    }

    /**
     * Returns true if the template exists.
     *
     * @param string|TemplateReferenceInterface $name A template name or a TemplateReferenceInterface instance
     *
     * @return bool    true if the template exists, false otherwise
     *
     * @throws \RuntimeException if the engine cannot handle the template name
     *
     * @api
     */
    public function exists($name)
    {
        //Todo
    }

}