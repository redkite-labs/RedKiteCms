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
namespace RedKiteCms\Rendering\Controller\Page;

/**
 * Class ApprovePageController is the object deputed to approve a page contribution
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Seo
 */
abstract class ApprovePageController extends BasePageController
{
    /**
     * Implements the action to approve the page contribution
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function approve(array $options)
    {
        $pageName = $options["request"]->get('pageName');
        $seoData = $options["request"]->get('seo-data');
        $json = $options["page_manager"]
            ->contributor($options["username"])
            ->approve($pageName, $seoData["language"]);

        return $this->buildJSonResponse($json);
    }
}