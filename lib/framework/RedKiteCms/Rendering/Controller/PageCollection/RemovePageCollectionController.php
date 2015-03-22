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

namespace RedKiteCms\Rendering\Controller\PageCollection;

/**
 * Class RemovePageCollectionController is the object deputed to remove a page collection from the website
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Rendering\Controller\Page
 */
abstract class RemovePageCollectionController extends BasePageCollectionController
{
    /**
     * Implements the action to remove a page collection
     * @param array $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove(array $options)
    {
        $data = $options["request"]->get('page-data');

        $options["page_collection_manager"]
            ->contributor($options["username"])
            ->remove($data["name"]);

        return $this->buildJSonResponse(array());
    }
}