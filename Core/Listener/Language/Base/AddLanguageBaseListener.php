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
use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Language\BeforeAddLanguageCommitEvent;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Abstract listener to the onBeforeAddLanguageCommit event
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
abstract class AddLanguageBaseListener
{
    private $sourceObjects = null;
    protected $mainLanguage = null;
    protected $languageManager = null;
    private $request = null;

    /**
     * Implement this method to set up the source objects
     */
    abstract protected function setUpSourceObjects();

    /**
     * Implement this method to copy the source objects to the new ones
     */
    abstract protected function copy(array $values);


    /**
     * Constructor
     *
     * @param Request $request
     */
    public function __construct(ContainerInterface $container = null)
    {
        if(null !== $container) {
            $this->request = $container->get('request');
        }
    }

    /**
     * Listen the onBeforeAddLanguageCommit event to copy the source object to the new language
     *
     * @param BeforeAddPageCommitEvent $event
     * @throws Exception
     */
    public function onBeforeAddLanguageCommit(BeforeAddLanguageCommitEvent $event)
    {
        if ($event->isAborted()) {
            return;
        }

        $this->languageManager = $event->getContentManager();
        $languageRepository = $this->languageManager->getLanguageModel();

        $this->mainLanguage = $languageRepository->mainLanguage();
        if(null === $this->mainLanguage) {
            $event->abort();

            return;
        }

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
                    $values = $sourceObject->toArray();
                    $result = $this->copy($values);
                    if(!$result) {
                        break;
                    }
                }

                if ($result) {
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

    /**
     * Fetches the base language used to copy the entities
     *
     * @return AlLanguage
     */
    protected function getBaseLanguage()
    {
        $languageRepository = $this->languageManager->getLanguageModel();

        // Tries to fetch the current language from the request
        if(null !== $this->request) {
            $languages = $this->request->getLanguages();

            $alLanguage = $languageRepository->fromLanguageName($languages[1]);
            if(null !== $alLanguage) {

                return $alLanguage;
            }
        }

        // Fetches the current language from the main language when the adding one is not the main language
        if($this->mainLanguage->getId() != $this->languageManager->get()->getId()) {

            return $this->mainLanguage;
        }

        return $languageRepository->firstOne();
    }
}

