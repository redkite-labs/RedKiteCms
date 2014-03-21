<?php
/**
 * This file is part of the RedKiteCmsBaseBlocksBundle and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteCms\Block\RedKiteCmsBaseBlocksBundle\Core\Block\File;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock\BlockManagerJsonBlockContainer;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\Asset;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\AssetsPath\AssetsPath;

/**
 * BlockManagerFile handles a file block
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlockManagerFile extends BlockManagerJsonBlockContainer
{
    protected $cmsLanguage;

    /**
     * Defines the App-Block's default value
     *
     * @return array
     */
    public function getDefaultValue()
    {
        $value = sprintf(
        '{
            "0" : {
                "file" : "%s",
                "description" : "",
                "opened" : false
            }
        }', $this->translator->translate('file_block_file_load', array(), 'RedKiteCmsBaseBlocksBundle'));

        return array(
            'Content' => $value,
        );
    }

    /**
     * Renders the App-Block's content view
     *
     * @return string|array
     */
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock);
        $item = $items[0];
        $file = $item['file'];
        $opened = $this->itemOpenedToBool($item);
        $description = (array_key_exists('description', $item)) ? $item['description'] : '';

        $kernel = $this->container->get('kernel');
        $deployBundle = $this->container->getParameter('red_kite_labs_theme_engine.deploy_bundle');
        $deployBundleAsset = new Asset($kernel, $deployBundle);

        return ($opened)
            ? sprintf("{%% set file = kernel_root_dir ~ '/../" . $this->container->getParameter('red_kite_cms.web_folder') . "/%s/%s' %%} {{ file_open(file) }}", $deployBundleAsset->getAbsolutePath(), $file)
            : sprintf('<a href="/%s/%s" />%s</a>', AssetsPath::getUploadFolder($this->container), $file, ( ! empty($description)) ? $description : basename($file));
    }

    /**
     * Defines the parameters passed to the App-Block's editor
     *
     * @return array
     */
    public function editorParameters()
    {
        $items = $this->decodeJsonContent($this->alBlock);
        $item = $items[0];
        $item['opened'] = $this->itemOpenedToBool($item);

        $formClass = $this->container->get('file.form');
        $form = $this->container->get('form.factory')->create($formClass, $item);

        return array(
            'template' => 'RedKiteCmsBundle:Block:Editor/_editor_form.html.twig',
            'title' => $this->translator->translate('file_block_editor_title', array(), 'RedKiteCmsBaseBlocksBundle'),
            'form' => $form->createView(),
        );
    }

    /**
     * Defines when a content is rendered or not in edit mode
     *
     * @return boolean
     */
    public function getHideInEditMode()
    {
        return true;
    }

    /**
     * Implements a method to let the derived class override it to format the content
     * to display when the Cms is active
     *
     * @return string|null
     */
    protected function replaceHtmlCmsActive()
    {
        $options = $this->getOptions();

        return array('RenderView' => array(
            'view' => 'RedKiteCmsBaseBlocksBundle:Content:File/file.html.twig',
            'options' => $options,
        ));
    }

    private function getOptions()
    {
        $items = $this->decodeJsonContent($this->alBlock);
        $item = $items[0];
        $item['opened'] = $this->itemOpenedToBool($item);
        $file = $item['file'];

        $options = array(
            'webfolder' => $this->container->getParameter('red_kite_cms.web_folder'),
            'folder' => AssetsPath::getUploadFolder($this->container),
            'filename' => $file,
        );

        if (! $item['opened']) {
            $options['displayValue'] = (array_key_exists('description', $item) && ! empty($item['description'])) ? $item['description'] : $file;
        }

        return $options;
    }

    private function itemOpenedToBool($item)
    {
        return array_key_exists('opened', $item) ? filter_var($item['opened'], FILTER_VALIDATE_BOOLEAN) : false;
    }
}
