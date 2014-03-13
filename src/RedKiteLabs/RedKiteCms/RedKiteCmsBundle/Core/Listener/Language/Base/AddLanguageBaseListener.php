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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Listener\Language\Base;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a base class to listen to onBeforeAddLanguageCommit event
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
abstract class AddLanguageBaseListener
{
    /** @var null|ContainerInterface */
    protected $container = null;
    /** @var null|\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Language\AlLanguageManager */
    protected $languageManager = null;
    /** @var null|\RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlLanguage */
    protected $mainLanguage = null;
    /** @var null|\Symfony\Component\HttpFoundation\Request */
    private $request = null;
    /** @var null|array|\PropelCollection */
    private $sourceObjects = null;

    /**
     * Implement this method to set up the source objects
     *
     * @return \PropelCollection A model collection instance depending on the used ORM (i.e PropelCollection)
     *
     * @api
     */
    abstract protected function setUpSourceObjects();

    /**
     * Implement this method to copy the source objects to the new ones
     *
     * @param  array   $values
     * @return boolean
     *
     * @api
     */
    abstract protected function copy(array $values);

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     *
     * @api
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
        if (null !== $container) {
            $this->request = $container->get('request');
        }
    }

    /**
     * Listen the onBeforeAddLanguageCommit event to copy the source object to the new language
     *
     * @param  BeforeAddLanguageCommitEvent $event
     * @return boolean
     * @throws \Exception
     *
     * @api
     */
    public function onBeforeAddLanguageCommit(BeforeAddLanguageCommitEvent $event)
    {
        if ($event->isAborted()) {
            return;
        }

        $this->languageManager = $event->getContentManager();
        $languageRepository = $this->languageManager->getLanguageRepository();

        $this->mainLanguage = $languageRepository->mainLanguage();
        if (null === $this->mainLanguage) {
            $event->abort();

            return;
        }

        $this->sourceObjects = $this->setUpSourceObjects();
        if (count($this->sourceObjects) > 0) {
            try {
                $result = true;
                $languageRepository->startTransaction();
                foreach ($this->sourceObjects as $sourceObject) {
                    $values = $sourceObject->toArray();
                    $result = $this->copy($values);
                    if (!$result) {
                        break;
                    }
                }

                if (false !== $result) {
                    $languageRepository->commit();
                } else {
                    $languageRepository->rollBack();

                    $event->abort();
                }
            } catch (\Exception $e) {
                $event->abort();
                if (isset($languageRepository) && $languageRepository !== null) {
                    $languageRepository->rollBack();
                }

                throw $e;
            }
        }
    }

    /**
     * Fetches the base language used to copy the entities
     *
     * @return \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\AlLanguage
     */
    protected function getBaseLanguage()
    {
        $languageRepository = $this->languageManager->getLanguageRepository();

        // Tries to fetch the current language from the request
        if (null !== $this->request) {
            $languages = $this->request->getLanguages();

            $alLanguage = $languageRepository->fromLanguageName($languages[1]);
            if (null !== $alLanguage) {
                return $alLanguage;
            }
        // @codeCoverageIgnoreStart
        }
        // @codeCoverageIgnoreEnd

        // Fetches the current language from the main language when the adding one is not the main language
        if ($this->mainLanguage->getId() != $this->languageManager->get()->getId()) {
            return $this->mainLanguage;
        }

        return $languageRepository->firstOne();
    }
}
