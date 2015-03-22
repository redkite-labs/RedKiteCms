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
 * Class PublishPageController is the object deputed to publish a page in production
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Page
 */
abstract class PublishPageController extends BasePageController
{
    /**
     * Implements the action to publish the page in production
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function publish(array $options)
    {
        $request = $options["request"];
        $pageName = $request->get('page');
        $languageName = $request->get('language') . '_' . $request->get('country');
        $options["page_manager"]
            ->contributor($options["username"])
            ->publish($pageName, $languageName);

        return $this->buildJSonResponse(array());
    }
}