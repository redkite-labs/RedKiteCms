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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Listener\Page;

use AlphaLemon\AlphaLemonCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Seo\AlSeoManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;

/**
 * Listen to the onBeforeAddPageCommit event to add the page's seo attributes, when a new page is added
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class AddSeoListener
{
    private $seoManager;
    private $languageRepository;

    /**
     * Constructor
     *
     * @param AlSeoManager                $seoManager
     * @param LanguageRepositoryInterface $languageRepository
     */
    public function __construct(AlSeoManager $seoManager, AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->seoManager = $seoManager;
        $this->languageRepository = $factoryRepository->createRepository('Language');
    }

    /**
     * Adds the page's seo attributes when a new page is added, for each language of the site
     *
     * @param  BeforeAddPageCommitEvent $event
     * @throws \Exception
     */
    public function onBeforeAddPageCommit(BeforeAddPageCommitEvent $event)
    {
        if ($event->isAborted()) {
            return;
        }

        $pageManager = $event->getContentManager();
        $pageRepository = $pageManager->getPageRepository();
        $values = $event->getValues();

        if (!is_array($values)) {
            throw new \InvalidArgumentException("The values param is expected to be an array");
        }

        try {
            $languages = $this->languageRepository->activeLanguages();
            if (count($languages)) {
                $result = true;
                $idPage = $pageManager->get()->getId();
                $this->seoManager->getSeoRepository()->setConnection($pageRepository->getConnection());
                $pageRepository->startTransaction();
                foreach ($languages as $alLanguage) {
                    $seoManagerValues = array_merge($values, array('PageId' => $idPage, 'LanguageId' => $alLanguage->getId()));
                    if (!$alLanguage->getMainLanguage() && array_key_exists('Permalink', $seoManagerValues)) $seoManagerValues['Permalink'] = $alLanguage->getLanguage() . '-' . $seoManagerValues['Permalink'];
                    $this->seoManager->set(null);
                    $result = $this->seoManager->save($seoManagerValues);

                    if (false === $result) break;
                }

                if (false !== $result) {
                    $pageRepository->commit();
                } else {
                    $pageRepository->rollBack();

                    $event->abort();
                }
            }
        } catch (\Exception $e) {
            $event->abort();

            if (isset($pageRepository) && $pageRepository !== null) {
                $pageRepository->rollBack();
            }

            throw $e;
        }
    }
}
