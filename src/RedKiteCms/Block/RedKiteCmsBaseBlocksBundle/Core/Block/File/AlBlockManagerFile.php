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

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;
use RedKiteLabs\ThemeEngineBundle\Core\Asset\AlAsset;
use RedKiteLabs\RedKiteCmsBundle\Core\AssetsPath\AlAssetsPath;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * AlBlockManagerFile handles a file block
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlBlockManagerFile extends AlBlockManagerJsonBlockContainer
{
    protected $translator;
    protected $cmsLanguage;
    
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($container, $validator);
        
        $this->translator = $this->container->get('red_kite_cms.translator');
    }
    
    public function getDefaultValue()
    {
        $value = sprintf(
        '{
            "0" : {
                "file" : "%s",
                "description" : "",
                "opened" : false
            }
        }', $this->translator->translate("Click to load a file"));

        return array(
            'Content' => $value,
        );
    }
    
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock);
        $item = $items[0];
        $file = $item['file'];
        $opened = $this->itemOpenedToBool($item);
        $description = (array_key_exists('description', $item)) ? $item['description'] : '';
        
        $kernel = $this->container->get('kernel');
        $deployBundle = $this->container->getParameter('red_kite_labs_theme_engine.deploy_bundle');
        $deployBundleAsset = new AlAsset($kernel, $deployBundle);

        return ($opened)
            ? sprintf("{%% set file = kernel_root_dir ~ '/../" . $this->container->getParameter('red_kite_cms.web_folder') . "/%s/%s' %%} {{ file_open(file) }}", $deployBundleAsset->getAbsolutePath(), $file)          
            : sprintf('<a href="/%s/%s" />%s</a>', AlAssetsPath::getUploadFolder($this->container), $file, ( ! empty($description)) ? $description : basename($file));
    }
        
    public function editorParameters()
    {        
        $items = $this->decodeJsonContent($this->alBlock);
        $item = $items[0];
        $item['opened'] = $this->itemOpenedToBool($item);
             
        $formClass = $this->container->get('file.form');
        $form = $this->container->get('form.factory')->create($formClass, $item); 
        
        return array(
            'template' => 'RedKiteCmsBundle:Block:Editor/_editor_form.html.twig',
            'title' => $this->translator->translate('Files editor'),
            'form' => $form->createView(),
        );
    }
    
    public function getHideInEditMode()
    {
        return true;
    }
    
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
            'folder' => AlAssetsPath::getUploadFolder($this->container),
            'filename' => $file,
        );
        
        if ( ! $item['opened']) {
            $options['displayValue'] = (array_key_exists('description', $item) && ! empty($item['description'])) ? $item['description'] : $file;
        }
        
        return $options;
    }
    
    private function itemOpenedToBool($item)
    {
        return array_key_exists('opened', $item) ? filter_var($item['opened'], FILTER_VALIDATE_BOOLEAN) : false;
    }
}