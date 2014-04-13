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

namespace RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\Navbar;

use RedKiteLabs\RedKiteCms\TwitterBootstrapBundle\Core\Block\Button\BlockManagerBootstrapButtonBlock;
use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorInterface;

/**
 * Defines the Block Manager to handle a Bootstrap navbar button
 *
 * @author RedKite Labs <info@redkite-labs.com>
 */
class BlockManagerBootstrapNavbarFormBlock extends BlockManagerBootstrapButtonBlock
{
    private $bootstrapVersion;

    public function __construct(ContainerInterface $container, ParametersValidatorInterface $validator = null)
    {
        parent::__construct($container, $validator);

        $this->bootstrapVersion = $this->container->get('red_kite_cms.active_theme')->getThemeBootstrapVersion();
    }

    /**
     * Defines the App-Block's default value
     *
     * @return array
     */
    public function getDefaultValue()
    {
        $alignment = 'navbar-left';
        if ($this->bootstrapVersion == '2.x') {
            $alignment = 'pull-left';
        }
        $value = '
            {
                "0" : {
                    "method": "POST",
                    "action": "#",
                    "enctype": "",
                    "placeholder": "Search",
                    "role": "Search",
                    "button_text": "Go",
                    "alignment": "' . $alignment . '"
                }
            }
        ';

        return array('Content' => $value);
    }

    /**
     * Renders the App-Block's content view
     *
     * @return string|array
     */
    protected function renderHtml()
    {
        $data = $this->decodeJsonContent($this->alBlock->getContent());

        $template = sprintf('TwitterBootstrapBundle:Content:Navbar/Form/%s/navbar_form.html.twig', $this->bootstrapVersion);

        return array('RenderView' => array(
            'view' => $template,
            'options' => array(
                'data' => $data[0],
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

        $bootstrapFormFactory = $this->container->get('twitter_bootstrap.bootstrap_form_factory');
        $form = $bootstrapFormFactory->createForm('Navbar\Form', 'NavbarFormType', $items[0]);

        return array(
            "template" => "TwitterBootstrapBundle:Editor:Navbar/Form/navbar_form_editor.html.twig",
            "title" => $this->translator->translate('navbar_form_editor_title', array(), 'TwitterBootstrapBundle'),
            "form" => $form->createView(),
        );
    }
}
