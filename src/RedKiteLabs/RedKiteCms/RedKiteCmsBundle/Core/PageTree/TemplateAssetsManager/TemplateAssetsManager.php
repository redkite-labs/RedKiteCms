<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use RedKiteLabs\ThemeEngineBundle\Core\Template\Template;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManagerFactoryInterface;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\AssetCollection;

/**
 * TemplateAssetsManager is the object deputed to collect assets parsing RedKite
 * CMS blocks and themes in use in the current website
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @method TemplateAssetsManager getExternalStylesheets() Returns the handled external stylesheets
 * @method TemplateAssetsManager getInternalStylesheets() Returns the handled internal stylesheets
 * @method TemplateAssetsManager getExternalJavascripts() Returns the handled external javascripts
 * @method TemplateAssetsManager getInternalJavascripts() Returns the handled internal javascripts
 */
class TemplateAssetsManager
{
    protected $template = null;
    protected $availableBlocks = array();
    private $assets = null;
    private $extraAssets = true;
    private $registeredListeners;

    /**
     * Constructor
     *
     * @param ContainerInterface             $container
     * @param BlockManagerFactoryInterface $blockManagerFactory
     */
    public function __construct(ContainerInterface $container, BlockManagerFactoryInterface $blockManagerFactory)
    {
        $this->container = $container;
        $this->blockManagerFactory = $blockManagerFactory;
        $this->availableBlocks = $this->blockManagerFactory->getAvailableBlocks();

        $this->registeredListeners = $this->container->get('red_kite_labs_theme_engine.registed_listeners');
    }

    /**
     * Creates magic methods
     *
     * @param  string $name   the method name
     * @param  mixed  $params the values to pass to the called method
     * @return mixed  Depends on method called
     */
    public function __call($name, $arguments)
    {
        if (null === $this->assets) {
            return null;
        }

        if (array_key_exists($name, $this->assets)) {
            return $this->assets[$name];
        }

        throw new \RuntimeException('TemplateAssetsManager does not support the method: ' . $name);
    }

    /**
     * Forces the TemplateAssetsManager to look for the requested parameter suffixed
     * by the cms siffix.
     *
     * @param  boolean                                                                                 $value
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\PageTree\TemplateAssetsManager\TemplateAssetsManager
     */
    public function withExtraAssets($value)
    {
        $this->extraAssets = $value;

        return $this;
    }

    /**
     * Sets up the object
     *
     * @param \RedKiteLabs\ThemeEngineBundle\Core\Template\Template $template
     * @param array                                                   $options
     */
    public function setUp(Template $template, array $options)
    {
        $this->template = $template;

        $this->assets = array();
        $methods = array(
            'getExternalStylesheets',
            'getExternalJavascripts',
            'getInternalStylesheets',
            'getInternalJavascripts',
        );
        foreach ($methods as $method) {
            $this->assets[$method] = $this->initAssets($method, $template, $options);
        }
    }

    /**
     * Merge assets for the method passed as argument
     *
     * @param  string                                                      $method
     * @param  Template                                                  $template
     * @param  array                                                       $options
     * @return \RedKiteLabs\ThemeEngineBundle\Core\Asset\AssetCollection
     */
    protected function mergeAssets($method, Template $template, array $options)
    {
        $assetsCollection = $template->$method();
        if (null !== $assetsCollection) {

            $assetsCollection = clone($assetsCollection);

            // merges extra assets from current theme
            $assetsCollection = $this->mergeFromTheme($assetsCollection, $template, $options);
            $assetsCollection = $this->mergeFromListener($assetsCollection, $options);
            $assetsCollection = $this->mergeAppBlocksAssets($assetsCollection, $options);
        }

        return $assetsCollection;
    }

    /**
     * Returns an array that contains the absolute path of each asset
     *
     * @param  string     $method
     * @param  Template $template
     * @param  array      $options
     * @return array
     */
    protected function initAssets($method, Template $template, array $options)
    {
        preg_match_all('/[^A-Z]?[A-Z]?[a-z]+/', $method, $matches);
        $options["type"] = strtolower($matches[0][1]);
        $options["assetType"] = strtolower($matches[0][2]);

        $assetsCollection = $this->mergeAssets($method, $template, $options);
        if (null === $assetsCollection) {
            return array();
        }

        $assets = array();
        foreach ($assetsCollection as $asset) {
            $absolutePath = $asset->getAbsolutePath();
            $originalAsset = $asset->getAsset();
            $assets[] = ($options["type"] == 'external') ? (empty($absolutePath)) ? $originalAsset : $absolutePath : $originalAsset;
        }

        return $assets;
    }

    /**
     * Merges the app block assets to the given collection
     *
     * @param  AssetCollection $assetsCollection
     * @param  array             $options
     * @return AssetCollection
     */
    protected function mergeAppBlocksAssets(AssetCollection $assetsCollection, array $options)
    {
        // When a block has examined, it is saved in this array to avoid parsing it again
        $appsAssets = array();

        // merges assets from installed apps

        foreach ($this->availableBlocks as $className) {
            if ( ! in_array($className, $appsAssets)) {
                $parameterSchema = '%s.%s_%s';
                $parameter = sprintf($parameterSchema, strtolower($className), $options["type"], $options["assetType"]);
                $this->addAssetsFromContainer($assetsCollection, $parameter);
                $this->addExtraAssets($assetsCollection, $parameter);

                $appsAssets[] = $className;
            }
        }

        return $assetsCollection;
    }

    /**
     * Adds a range of assets to the assets collection fetching from the container
     *
     * @param AssetCollection $assetsCollection
     * @param string            $parameter        The parameter to fetch from the Container
     */
    protected function addAssetsFromContainer(AssetCollection &$assetsCollection, $parameter)
    {
        if ( ! $this->container->hasParameter($parameter)) {
            return;
        }

        $assets = $this->container->getParameter($parameter);
        $assetsCollection->addRange($assets);
    }

    /**
     * Adds to the assets collection the extra parameters defined by extraAssetsSuffixes
     *
     * @param AssetCollection $assetsCollection
     * @param string            $baseParam
     */
    protected function addExtraAssets(&$assetsCollection, $baseParam)
    {
        if (! $this->extraAssets) {
            return;
        }

        $parameter = sprintf('%s.cms', $baseParam);
        $this->addAssetsFromContainer($assetsCollection, $parameter);
    }

    private function mergeFromTheme($assetsCollection, $template, array $options)
    {
        $themeName = $template->getThemeName();
        $themeBasename = str_replace('Bundle', '', $themeName);
        $extensionAlias = Container::underscore($themeBasename);
        $parameter = sprintf('%s.%s.%s_%s', $extensionAlias, $template->getTemplateName(), $options["type"], $options["assetType"]);
        $this->addExtraAssets($assetsCollection, $parameter);

        return $assetsCollection;
    }

    private function mergeFromListener($assetsCollection, array $options)
    {
        // merges assets for theme engine registered listeners
        foreach ($this->registeredListeners as $registeredListener) {
            // Assets from page_renderer.before_page_rendering listeners
            $parameter = sprintf('%s.page.%s_%s', $registeredListener, $options["type"], $options["assetType"]);
            $this->addAssetsFromContainer($assetsCollection, $parameter);

            // Assets from page_renderer.before_[language]_rendering listeners
            if (array_key_exists('language', $options)) {
                $parameter = sprintf('%s.%s.%s_%s', $registeredListener, $options["language"], $options["type"], $options["assetType"]);
                $this->addAssetsFromContainer($assetsCollection, $parameter);
            }

            // Assets from page_renderer.before_[page]_rendering listeners
            if (array_key_exists('page', $options)) {
                $parameter = sprintf('%s.%s.%s_%s', $registeredListener, $options["page"], $options["type"], $options["assetType"]);
                $this->addAssetsFromContainer($assetsCollection, $parameter);
            }
        }

        return $assetsCollection;
    }
}
