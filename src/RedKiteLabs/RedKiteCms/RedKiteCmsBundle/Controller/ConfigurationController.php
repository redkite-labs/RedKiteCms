<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigurationController extends Base\BaseController
{
    public function changeCmsLanguageAction(Request $request)
    {
        $languageName = $request->get('languageName');

        $configurationRepository = $this->createRepository('Configuration');
        $configuration = $configurationRepository->fetchParameter('language');
        $result = $configurationRepository
            ->setRepositoryObject($configuration)
            ->save(array('Value' => $languageName))
        ;

        $params = array();
        $statusCode = 404;
        switch ($result) {
            case null:
                $message = 'configuration_controller_language_already_in_use';
                $params = array(
                    '%language%' => $languageName,
                );
                break;
            case false:
                // @codeCoverageIgnoreStart
                $message = 'configuration_controller_changing_language_error';
                break;
                // @codeCoverageIgnoreEnd
            case true:
                $message = 'configuration_controller_cms_language_changed';
                $statusCode = 200;
                break;
        }

        return new Response($this->translate($message, $params), $statusCode);
    }
}
