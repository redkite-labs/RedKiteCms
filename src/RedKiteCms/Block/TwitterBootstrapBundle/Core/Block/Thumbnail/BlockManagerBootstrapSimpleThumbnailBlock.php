<?php
/*
 * This file is part of the TwitterBootstrapBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright
 * notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Thumbnail;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\JsonBlock\BlockManagerJsonBlockContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorInterface;

/**
 * Defines the Block Manager to handle the Bootstrap Thumbnail
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapSimpleThumbnailBlock extends BlockManagerJsonBlockContainer
{
    protected $blockTemplate;
    protected $editorTemplate = 'TwitterBootstrapBundle:Editor:Thumbnail/editor.html.twig';
    protected $bootstrapVersion;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface                           $container
     * @param \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(ContainerInterface $container, ParametersValidatorInterface $validator = null)
    {
        parent::__construct($container, $validator);

        $this->bootstrapVersion = $this->container->get('red_kite_cms.active_theme')->getThemeBootstrapVersion();

        $this->blockTemplate = sprintf('TwitterBootstrapBundle:Content:Thumbnail/%s/simple_thumbnail.html.twig', $this->bootstrapVersion);
    }

    /**
     * Defines the App-Block's default value
     *
     * @return array
     */
    public function getDefaultValue()
    {
        $columnValue = ($this->bootstrapVersion == '2.x') ? "span3" : "col-md-5";

        $value = '
            {
                "0" : {
                    "width": "' . $columnValue . '"
                }
            }';

        return array('Content' => $value);
    }

    /**
     * Renders the App-Block's content view
     *
     * @return string|array
     */
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];

        return array('RenderView' => array(
            'view' => $this->blockTemplate,
            'options' => array(
                'thumbnail' => $item,
            ),
        ));
    }

    /**
     * Defines the parameters passed to the App-Block's editor
     *
     * @return array
     */
    public function editorParameters()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];

        $bootstrapFormFactory = $this->container->get('twitter_bootstrap.bootstrap_form_factory');
        $form = $bootstrapFormFactory->createForm('Thumbnail', 'ThumbnailType', $item);

        return array(
            "template" => $this->editorTemplate,
            "title" => $this->translator->translate('thumbnail_width_attribute', array(), 'TwitterBootstrapBundle'),
            "form" => $form->createView(),
        );
    }

    /**
     * Defines when a block is internal, so it must not be available in the add blocks
     * menu
     *
     * @return boolean
     */
    public function getIsInternalBlock()
    {
        return true;
    }
}
