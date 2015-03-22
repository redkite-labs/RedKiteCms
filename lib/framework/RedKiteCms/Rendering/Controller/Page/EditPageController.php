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

use RedKiteCms\Tools\Utils;

/**
 * Class EditPageController is the object deputed to edit a page
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Seo
 */
abstract class EditPageController extends BasePageController
{
    /**
     * Implements the action to edit the page
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(array $options)
    {
        $pageName = $options["request"]->get('page-name');
        $seoData = $options["request"]->get('seo-data');
        $json = $options["page_manager"]
            ->contributor($options["username"])
            ->edit($pageName, $seoData);
        $this->updateSessionPermalink($options);

        return $this->buildJSonResponse($json);
    }

    private function updateSessionPermalink(array $options)
    {
        $request = $options["request"];
        $changedPermalink = $options["page_manager"]->getChangedPermalink();
        if (!empty($changedPermalink) && Utils::slugify(
                $request->getSession()->get('last_route')
            ) == 'get-backend-' . $changedPermalink["old"]
        ) {
            $request->getSession()->set('last_route', 'GET_backend_' . str_replace("-", "_", $changedPermalink["new"]));
        }
    }
}