<?php
/**
 * This file is part of the TinyMceBlockBundle and it is distributed
 * under the MIT LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license     MIT LICENSE
 *
 */

namespace RedKiteCms\Block\TinyMceBlockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Finder;

/**
 * TinyMceController
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class TinyMceController extends Controller
{
    public function createLinksListAction()
    {
        $factoryRepository = $this->container->get('red_kite_cms.factory_repository');
        $seoRepository = $factoryRepository->createRepository('Seo');
        $seoAttributes = $seoRepository->fromLanguageName($this->getRequest()->get('language'));
        
        $mceLinks = array();
        foreach ($seoAttributes as $seoAttribute) {
            $permalink = $seoAttribute->getPermalink();
            $mceLinks[] = array(
                'title' => $permalink,
                'value' => $permalink,
            );
            
        }
        
        $response = new Response();
        $response->setContent(json_encode($mceLinks));

        return $response;
        
        return $this->setResponse();
    }
}
