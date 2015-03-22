<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Rendering\Controller\Block;

use RedKiteCms\Rendering\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BaseBlockController is the base class to define a block controller
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Block
 */
abstract class BaseBlockController extends BaseController
{
    /**
     * Configures the base options for the resolver
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            array(
                'request',
                'username',
                'red_kite_cms_config',
                'block_factory',
                'serializer',
            )
        );

        $resolver->setAllowedTypes(
            array(
                'username' => array(
                    'null',
                    'string',
                ),
                'request' => '\Symfony\Component\HttpFoundation\Request',
                'red_kite_cms_config' => '\RedKiteCms\Configuration\ConfigurationHandler',
                'block_factory' => '\RedKiteCms\Content\Block\BlockFactory',
                'serializer' => '\JMS\Serializer\Serializer',
            )
        );
    }

    /**
     * Returns the base block name
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getBlockName(Request $request)
    {
        $blockName = $request->get('name');
        if (empty($blockName)) {
            $blockName = "block1";
        }

        return $blockName;
    }
}