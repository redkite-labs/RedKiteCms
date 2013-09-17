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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Listener\Page;

use RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Page\BeforeEditPageCommitEvent;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Seo\AlSeoManager;
use \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\InvalidArgumentException;

/**
 * Listen to the onBeforeEditPageCommit event to edit the seo attributes when a new
 * page is edited
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class EditSeoListener
{
    private $seoManager;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Seo\AlSeoManager $seoManager
     *
     * @api
     */
    public function __construct(AlSeoManager $seoManager)
    {
        $this->seoManager = $seoManager;
    }

    /**
     * Edits the seo attributes when a new page is edited
     *
     * @param  \RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Page\BeforeEditPageCommitEvent $event
     * @return boolean
     * @throws \InvalidArgumentException
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Listener\Page\Exception
     *
     * @api
     */
    public function onBeforeEditPageCommit(BeforeEditPageCommitEvent $event)
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
            $idPage = $pageManager->get()->getId();
            $idLanguage = $pageManager->getTemplateManager()
                    ->getPageBlocks()
                    ->getIdLanguage();
            $seoRepository = $this->seoManager->getSeoRepository();
            $seo = $seoRepository->fromPageAndLanguage($idLanguage, $idPage);
            if (!empty($values)) {
                $seoRepository->setConnection($pageRepository->getConnection());
                $pageRepository->startTransaction();
                $this->seoManager->set($seo);
                $values = array_merge($values, array('PageId' => $idPage, 'LanguageId' => $idLanguage));
                $result = $this->seoManager->save($values);

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
