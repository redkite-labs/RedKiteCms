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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Listener\Page;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Event\Content\Page\BeforeAddPageCommitEvent;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Seo\AlSeoManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException;

/**
 * Listen to the onBeforeAddPageCommit event to add the page's seo attributes, when
 * a new page is added
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AddSeoListener
{
    /** @var AlSeoManager */
    private $seoManager;
    /** @var \RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Repository\LanguageRepositoryInterface */
    private $languageRepository;

    /**
     * Constructor
     *
     * @param AlSeoManager                 $seoManager
     * @param AlFactoryRepositoryInterface $factoryRepository
     *
     * @api
     */
    public function __construct(AlSeoManager $seoManager, AlFactoryRepositoryInterface $factoryRepository)
    {
        $this->seoManager = $seoManager;
        $this->languageRepository = $factoryRepository->createRepository('Language');
    }

    /**
     * Adds the page's seo attributes when a new page is added, for each language of
     * the site
     *
     * @param  BeforeAddPageCommitEvent $event
     * @return boolean
     * @throws InvalidArgumentException
     * @throws \Exception
     *
     * @api
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
            throw new InvalidArgumentException('exception_invalid_value_array_required');
        }

        try {
            $languages = $this->languageRepository->activeLanguages();
            if (count($languages)) {
                $result = true;
                $idPage = $pageManager->get()->getId();
                $this->seoManager->getSeoRepository()->setConnection($pageRepository->getConnection());
                $pageRepository->startTransaction();
                foreach ($languages as $alLanguage) {
                    $seoManagerValues = array_merge($values, array('PageId' => $idPage, 'LanguageId' => $alLanguage->getId(), 'CreatedAt'       => date("Y-m-d H:i:s")));
                    if (!$alLanguage->getMainLanguage() && array_key_exists('Permalink', $seoManagerValues)) $seoManagerValues['Permalink'] = $alLanguage->getLanguageName() . '-' . $seoManagerValues['Permalink'];
                    $this->seoManager->set(null);
                    $result = $this->seoManager->save($seoManagerValues);

                    if (false === $result) break;
                }

                if (false !== $result) {
                    $pageRepository->commit();

                    return;
                }

                $pageRepository->rollBack();
                $event->abort();
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
