<?php
/**
 * Created by PhpStorm.
 * User: vilma
 * Date: 02.08.18
 * Time: 09:34
 */

namespace OxidEsales\EshopCommunity\Internal\Smarty;

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
     * @param string $name       The name of the template
     * @param array  $parameters Parameters to assign
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

    /**
     * Checks whether the specified template exists.
     * It can accept either a path to the template on the filesystem or a resource string specifying the template.
     *
     * @param string $name A template name or a TemplateReferenceInterface instance
     *
     * @return bool True if the template exists, false otherwise
     */
    public function exists($name)
    {
        return $this->engine->template_exists($name);
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
     */
    public function supports($name)
    {
        //Todo
    }

    /**
     * Pass parameters to the Smarty instance.
     *
     * @param string $name  The name of the parameter.
     * @param mixed  $value The value of the parameter.
     */
    public function __set($name, $value)
    {
        $this->engine->$name = $value;
    }

    /**
     * Pass parameters to the Smarty instance.
     *
     * @param string $name The name of the parameter.
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->engine->$name;
    }
}