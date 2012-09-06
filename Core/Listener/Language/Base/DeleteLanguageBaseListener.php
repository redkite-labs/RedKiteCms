<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Language\Base;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeDeleteLanguageCommitEvent;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Abstract listener to the onBeforeDeleteLanguageCommit event
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
abstract class DeleteLanguageBaseListener
{
    private $sourceObjects = null;
    protected $mainLanguage = null;
    protected $languageManager = null;

    /**
     * Implement this method to set up the source objects
     */
    abstract protected function setUpSourceObjects();

    /**
     * Implement this method to delete the source objects to the new ones
     */
    abstract protected function delete($object);


    /**
     * Listen the onBeforeDeleteLanguageCommit event to delete the source object to the new language
     *
     * @param BeforeDeleteLanguageCommitEvent $event
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
        if(null === $this->sourceObjects) {
            return;
        }

        if (count($this->sourceObjects) > 0) {
            try {
                $result = true;
                $languageRepository->startTransaction();
                foreach($this->sourceObjects as $sourceObject)
                {
                    $result = $this->delete($sourceObject);
                    if(!$result) {
                        break;
                    }
                }

                if (false !== $result) {
                    $languageRepository->commit();
                }
                else {
                    $languageRepository->rollBack();

                    $event->abort();
                }
            }
            catch(\Exception $e) {
                $event->abort();
                if (isset($languageRepository) && $languageRepository !== null) {
                    $languageRepository->rollBack();
                }

                throw $e;
            }
        }
    }
}

