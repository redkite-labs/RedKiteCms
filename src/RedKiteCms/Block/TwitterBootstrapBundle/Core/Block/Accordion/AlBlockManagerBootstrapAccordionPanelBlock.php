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

namespace RedKiteCms\Block\TwitterBootstrapBundle\Core\Block\Accordion;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * Defines the Block Manager to handle the Bootstrap Thumbnail
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class AlBlockManagerBootstrapAccordionPanelBlock extends AlBlockManagerJsonBlockContainer
{
    protected $blockTemplate;
    protected $editorTemplate = 'TwitterBootstrapBundle:Editor:AccordionPanel/editor.html.twig';
    protected $bootstrapVersion;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface                           $container
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($container, $validator);

        $this->bootstrapVersion = $this->container->get('red_kite_cms.active_theme')->getThemeBootstrapVersion();

        $this->blockTemplate = sprintf('TwitterBootstrapBundle:Content:AccordionPanel/%s/accordion_panel.html.twig', $this->bootstrapVersion);
    }

    /**
     * Defines the App-Block's default value
     *
     * @return array
     */
    public function getDefaultValue()
    {
        $value = '
            {
                "0" : {
                    "0": "accordion"
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
            'options' => array(),
        ));
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
