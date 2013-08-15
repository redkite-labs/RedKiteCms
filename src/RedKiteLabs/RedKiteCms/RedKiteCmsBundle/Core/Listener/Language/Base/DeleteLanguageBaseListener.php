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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Listener\Language\Base;

use RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Language\BeforeDeleteLanguageCommitEvent;

/**
 * Provides a base class to listen to onBeforeDeleteLanguageCommit event
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class DeleteLanguageBaseListener
{
    private $sourceObjects = null;
    protected $mainLanguage = null;
    protected $languageManager = null;

    /**
     * Implement this method to set up the source objects
     *
     * @return A model collection instance depending on the used ORM (i.e PropelCollection)
     */
    abstract protected function setUpSourceObjects();

    /**
     * Implement this method to delete the source objects to the new ones
     *
     * @return null|A model collection instance depending on the used ORM (i.e PropelCollection)
     */
    abstract protected function delete($object);

    /**
     * Listen the onBeforeDeleteLanguageCommit event to delete the source object to the new language
     *
     * @param  BeforeDeleteLanguageCommitEvent $event
     * @throws Exception
     */
    public function onBeforeDeleteLanguageCommit(BeforeDeleteLanguageCommitEvent $event)
    {
        if ($event->isAborted()) {
            return;
        }

        $this->languageManager = $event->getContentManager();
        $languageRepository = $this->languageManager->getLanguageRepository();

        $this->sourceObjects = $this->setUpSourceObjects();
        if (null === $this->sourceObjects) {
            return;
        }

        if (count($this->sourceObjects) > 0) {
            try {
                $result = true;
                $languageRepository->startTransaction();
                foreach ($this->sourceObjects as $sourceObject) {
                    $result = $this->delete($sourceObject);
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
}
