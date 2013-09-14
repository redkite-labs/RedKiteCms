<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class ConfigurationController extends Base\BaseController
{
    public function changeCmsLanguageAction()
    {
        try {
            $request = $this->container->get('request');
            $languageName = $request->get('languageName');  
            
            $factoryRepository = $this->container->get('red_kite_cms.factory_repository');
            $configurationRepository = $factoryRepository->createRepository('Configuration');
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
        } catch (\Exception $e) {
            // @codeCoverageIgnoreStart
            return $this->renderDialogMessage($e->getMessage());
            // @codeCoverageIgnoreEnd
        }
    }
}
