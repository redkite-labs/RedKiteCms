<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
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

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\General\RuntimeException;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\ModelChoiceValues\ChoiceValues;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Form\Language\LanguagesForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LanguagesController extends Base\BaseController
{
    public function indexAction(Request $request)
    {
        // @codeCoverageIgnoreStart
        if (!extension_loaded('intl')) {
            throw new RuntimeException('languages_controller_intl_extension_not_enabled');
        }
        // @codeCoverageIgnoreEnd

        $form = $this->createForm(new LanguagesForm());

        $params = array(
            'languages' => ChoiceValues::getLanguages($this->createLanguageRepository(), false),
            'active_language' => $request->get('language'),
            'form' => $form->createView(),
        );

        return $this->render('RedKiteCmsBundle:Languages:panel.html.twig', $params);
    }

    public function saveLanguageAction(Request $request)
    {
        $languageManager = $this->container->get('red_kite_cms.language_manager');
        $alLanguage = $this->fetchLanguage($request->get('languageId'), $languageManager);
        $languageManager->set($alLanguage);

        $parameters = array();
        $isMain = $request->get('isMain');
        if (null !== $isMain) {
            $parameters['MainLanguage'] =  $isMain;
        }

        $newLanguage = $request->get('newLanguage');
        if (null !== $newLanguage) {
            $parameters['LanguageName'] =  $newLanguage;
        }

        if ($languageManager->save($parameters)) {
            $language = $languageManager->getLanguageRepository()->fromLanguageName($request->get('language'));

            $message = $this->translate('languages_controller_language_saved');

            return $this->buildJSonHeader($request, $message, $language);
        }

        // @codeCoverageIgnoreStart
        throw new RuntimeException('languages_controller_unespected_error_when_saving_language');
        // @codeCoverageIgnoreEnd
    }

    public function deleteLanguageAction(Request $request)
    {
        $languageId = $request->get('languageId');
        if ((int) $languageId == 0) {
            throw new RuntimeException('languages_controller_any_language_selected_for_removing');
        }

        $languageManager = $this->container->get('red_kite_cms.language_manager');
        $alLanguage = $this->fetchLanguage($languageId, $languageManager);
        if (null === $alLanguage) {
            throw new RuntimeException('languages_controller_any_language_selected_for_removing');
        }

        $result = $languageManager
            ->set($alLanguage)
            ->delete()
        ;
        if ($result) {
            $message = $this->translate('languages_controller_language_delete');

            return $this->buildJSonHeader($request, $message, $alLanguage);
        }

        // @codeCoverageIgnoreStart
        throw new RuntimeException('languages_controller_language_not_delete');
        // @codeCoverageIgnoreEnd
    }

    public function loadLanguageAttributesAction(Request $request)
    {
        $values = array();
        $languageId = $request->get('languageId');
        if ($languageId != 'none') {
            $alLanguage = $this->fetchLanguage($languageId);
            $values[] = array("name" => "#languages_language", "value" => $alLanguage->getLanguageName());
            $values[] = array("name" => "#languages_isMain", "value" => $alLanguage->getMainLanguage());
        }

        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    protected function buildJSonHeader(Request $request, $message, $language = null)
    {
        $languages = $languagesList = ChoiceValues::getLanguages($this->createLanguageRepository());
        unset($languagesList['none']);

        $values = array();
        $values[] = array("key" => "message", "value" => $message);
        $values[] = array(
            "key" => "languages",
            "value" => $this->renderView(
                'RedKiteCmsBundle:Languages:languages_list.html.twig',
                array(
                    'languages' => $languagesList,
                    'active_language' => $request->get('language')
                )
            )
        );
        $values[] = array(
            "key" => "languages_menu",
            "value" => $this->renderView(
                'RedKiteCmsBundle:Partials:_dropdown_menu.html.twig',
                array(
                    'id' => 'al_languages_navigator',
                    'type' => 'al_language_item',
                    'value' => (null !== $language) ? $language->getId() : 0,
                    'text' => $request->get('language'),
                    'items' => $languages
                )
            )
        );

        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface
     */
    private function createLanguageRepository()
    {
        return $this->createRepository('Language');
    }

    /**
     * @param $id
     * @param  \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Language\AlLanguageManager|null $languageManager
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlLanguage|null
     */
    private function fetchLanguage($id, $languageManager = null)
    {
        $languageManager = (null === $languageManager) ? $this->container->get('red_kite_cms.language_manager') : $languageManager;
        $languageRepository = $languageManager->getLanguageRepository();

        return ($id != 'none') ? $languageRepository->fromPk($id) : null;
    }
}
