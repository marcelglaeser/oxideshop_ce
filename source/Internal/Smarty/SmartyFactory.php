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
class SmartyFactory implements SmartyFactoryInterface
{
    /**
     * @var \Smarty
     */
    private $smarty;

    /**
     * Set properties for smarty:
     * [
     *   'options' => 'smartyCommonOptions',
     *   'securityOptions' => 'smartySecurityOptions',
     *   'plugins' => 'smartyPluginsToRegister',
     *   'prefilters' => 'smartyPreFiltersToRegister',
     *   'resources' => 'smartyResourcesToRegister',
     * ]
     *
     * @var array
     */
    private $properties;

    /**
     * SmartyEnvironment constructor.
     *
     * @param TemplateEngineConfigurationInterface $configuration
     */
    public function __construct(TemplateEngineConfigurationInterface $configuration)
    {
        $this->properties = $configuration->getParameters();
    }

    public function getSmarty()
    {
        $this->smarty = new \Smarty();

        $this->fillSmartyProperties($this->properties);
        $this->registerPlugins($this->properties['plugins']);
        $this->registerPrefilters($this->properties['prefilters']);
        $this->registerResources($this->properties['resources']);

        return $this->smarty;
    }

    /**
     * Sets properties of smarty object.
     *
     * @param array $properties Smarty option.
     */
    private function fillSmartyProperties($properties)
    {
        //set options
        foreach ($properties['options'] as $key => $value) {
            $this->smarty->$key = $value;
        }

        $this->setSecuritySettings($properties['securityOptions']);
    }

    /**
     * Registers a resource of smarty object.
     *
     * @param array $resourcesToRegister The Resources to fetch a template.
     */
    private function registerResources($resourcesToRegister)
    {
        foreach ($resourcesToRegister as $key => $resources) {
            $this->smarty->register_resource($key, $resources);
        }
    }

    /**
     * Sets security options of smarty object.
     *
     * @param array $securityOptions
     */
    private function setSecuritySettings($securityOptions)
    {
        //set options
        foreach ($securityOptions as $key => $value) {
            $this->smarty->$key = $value;
        }

        if (isset($securityOptions['security_settings'])) {
            $securitySettings = $securityOptions['security_settings'];
            foreach ($securitySettings as $key => $value) {
                if (is_array($value)) {
                    $this->smarty->security_settings[$key][] = $value;
                } else {
                    $this->smarty->security_settings[$key] = $value;
                }
            }
        }
    }

    /**
     * Register prefilters of smarty object.
     *
     * @param array $prefilters
     */
    private function registerPrefilters($prefilters)
    {
        foreach ($prefilters as $prefilter => $path) {
            include_once $path;
            $this->smarty->register_prefilter($prefilter);
        }
    }

    /**
     * Register plugins of smarty object.
     *
     * @param array $plugins
     */
    private function registerPlugins($plugins)
    {
        $this->smarty->plugins_dir = array_merge($plugins, $this->smarty->plugins_dir);
    }
}