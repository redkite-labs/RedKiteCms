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
 * Class HidePageController is the object deputed to hide a published page from production
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Seo
 */
abstract class HidePageController extends BasePageController
{
    /**
     * Implements the action to hide a page from production
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function hide(array $options)
    {
        $request = $options["request"];
        $pageName = $request->get('page');
        $languageName = $request->get('language') . '_' . $request->get('country');
        $options["page_manager"]
            ->contributor($options["username"])
            ->hide($pageName, $languageName);

        return $this->buildJSonResponse(array());
    }
}