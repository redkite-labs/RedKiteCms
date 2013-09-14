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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Seo;

use RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Model\AlSeo;
use RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\SeoEvents;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\AlContentManagerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Base\AlContentManagerBase;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\SeoRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Page\AlPageManager;

/**
 * AlSeoManager is the base object that wraps an AlSeo object
 *
 * AlSeoManager manages an AlSeo object, implementig the base methods to add, edit
 * and delete that kind of object.
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
class AlSeoManager extends AlContentManagerBase implements AlContentManagerInterface
{
    /**
     * @var \RedKiteLabs\RedKiteCmsBundle\Model\AlSeo
     */
    protected $alSeo = null;

    /**
     * @var \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface
     */
    protected $factoryRepository = null;

    /**
     * @var \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\SeoRepositoryInterface
     */
    protected $seoRepository = null;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface           $eventsHandler
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface  $factoryRepository
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(AlEventsHandlerInterface $eventsHandler, AlFactoryRepositoryInterface $factoryRepository, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($eventsHandler, $validator);

        $this->factoryRepository = $factoryRepository;
        $this->seoRepository = $this->factoryRepository->createRepository('Seo');
    }

    /**
     * Sets the seo model object
     *
     * @param  \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Repository\SeoRepositoryInterface $v
     * @return \RedKiteLabs\RedKiteCmsBundle\Core\Content\Seo\AlSeoManager
     *
     * @api
     */
    public function setSeoRepository(SeoRepositoryInterface $v)
    {
        $this->seoRepository = $v;

        return $this;
    }

    /**
     * Returns the seo model object associated with this object
     *
     * @return SeoRepositoryInterface
     *
     * @api
     */
    public function getSeoRepository()
    {
        return $this->seoRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->alSeo;
    }

    /**
     * {@inheritdoc}
     */
    public function set($object = null)
    {
        if (null !== $object && !$object instanceof AlSeo) {
            throw new General\InvalidArgumentTypeException('exception_only_seo_objects_are_accepted');
        }

        $this->alSeo = $object;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $values)
    {
        if (null === $this->alSeo || $this->alSeo->getId() == null) {
            return $this->add($values);
        }

        return $this->edit($values);
    }

    /**
     * {@inheritdoc}
     *
     * @return boolean
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
     * 
     * @api
     */
    public function delete()
    {
        if (null === $this->alSeo) {
            $exception = array(
                'message' => 'exception_seo_is_null',
                'parameters' => array(
                    '%className%' => get_class($this),
                ),
            );
            
            throw new General\ArgumentIsEmptyException(json_encode($exception));
        }
        
        $this->dispatchBeforeOperationEvent(
            '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Seo\BeforeSeoDeletingEvent',
            SeoEvents::BEFORE_DELETE_SEO,
            array(),
            array(
                'message' => 'exception_seo_deleting_aborted',
                'domain' => 'exceptions',
            )
        );
        
        try {
            $this->seoRepository->startTransaction();
            $result = $this->seoRepository
                        ->setRepositoryObject($this->alSeo)
                        ->delete();
            if (false !== $result) {
                $eventName = SeoEvents::BEFORE_DELETE_SEO_COMMIT;
                $result = !$this->eventsHandler
                            ->createEvent($eventName, '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Seo\BeforeDeleteSeoCommitEvent', array($this, array()))
                            ->dispatch()
                            ->getEvent($eventName)
                            ->isAborted();
            }

            if (false !== $result) {
                $this->seoRepository->commit();

                $this->eventsHandler
                     ->createEvent(SeoEvents::AFTER_DELETE_SEO, '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Seo\AfterSeoDeletedEvent', array($this))
                     ->dispatch();
                
                return $result;
            }
            $this->seoRepository->rollBack();

            return $result;
        } catch (\Exception $e) {
            if (isset($this->seoRepository) && $this->seoRepository !== null) {
                $this->seoRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Deletes the seo attribute identified by the given language and page
     *
     * @param  int     $languageId
     * @param  int     $pageId
     * @return boolean
     *
     * @api
     */
    public function deleteSeoAttributesFromLanguage($languageId, $pageId)
    {
        $alSeo = $this->seoRepository->fromPageAndLanguage($languageId, $pageId);
        // Occours when the attributes has been already removed
        if (null === $alSeo) return true;

        $this->set($alSeo);
        $result = $this->delete();
        $this->set(null);

        return $result;
    }

    /**
     * Adds a new AlSeo object from the given params
     *
     * @param  array                                                                                        $values
     * @return boolean
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\ArgumentIsEmptyException
     * 
     * @api
     */
    protected function add(array $values)
    {
        $values =
            $this->dispatchBeforeOperationEvent(
                '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Seo\BeforeSeoAddingEvent',
                SeoEvents::BEFORE_ADD_SEO,
                $values,
                array(
                    'message' => 'exception_seo_adding_aborted',
                    'domain' => 'exceptions',
                )
            );

        try {
            $this->validator->checkEmptyParams($values);
            $this->validator->checkRequiredParamsExists(array('PageId' => '', 'LanguageId' => '', 'Permalink' => ''), $values);

            if (empty($values['PageId'])) {
                throw new General\ArgumentIsEmptyException('exception_page_id_required_to_save_seo');
            }

            if (empty($values['LanguageId'])) {
                throw new General\ArgumentIsEmptyException('exception_language_id_required_to_save_seo');
            }

            if (empty($values['Permalink'])) {
                throw new General\ArgumentIsEmptyException('exception_permalink_required_to_save_seo');
            }
                    
            $values["Permalink"] = AlPageManager::slugify($values["Permalink"]);

            $this->seoRepository->startTransaction();
            if (null === $this->alSeo) {
                $className = $this->seoRepository->getRepositoryObjectClassName();
                $this->alSeo = new $className();
            }

            $result = $this->seoRepository
                    ->setRepositoryObject($this->alSeo)
                    ->save($values);
            if (false !== $result) {
                $eventName = SeoEvents::BEFORE_ADD_SEO_COMMIT;
                $result = !$this->eventsHandler
                                ->createEvent($eventName, '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Seo\BeforeAddSeoCommitEvent', array($this, $values))
                                ->dispatch()
                                ->getEvent($eventName)
                                ->isAborted();
            }

            if (false !== $result) {
                $this->seoRepository->commit();

                $this->eventsHandler
                     ->createEvent(SeoEvents::AFTER_ADD_SEO, '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Seo\AfterSeoAddedEvent', array($this))
                     ->dispatch();
                     
                return $result;
            }
            
            $this->seoRepository->rollBack();

            return $result;
        } catch (General\ArgumentIsEmptyException $ex) {
        } catch (General\EmptyArgumentsException $ex) {
        } catch (General\ArgumentExpectedException $ex) {
        } catch (\Exception $e) {
            if (isset($this->seoRepository) && $this->seoRepository !== null) {
                $this->seoRepository->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Edits the managed page attributes object
     *
     * @param  array                                                                                        $values
     * @return boolean
     * @throws \RedKiteLabs\RedKiteCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException
     * 
     * @api
     */
    protected function edit(array $values = array())
    {
        $values =
            $this->dispatchBeforeOperationEvent(
                '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Seo\BeforeSeoEditingEvent',
                SeoEvents::BEFORE_EDIT_SEO,
                $values,
                array(
                    'message' => 'exception_seo_editing_aborted',
                    'domain' => 'exceptions',
                )
            );

        try {
            if (isset($values['Permalink'])) {
                $currentPermalink = $this->alSeo->getPermalink();
                if ($values['Permalink'] != $currentPermalink) {
                    $values["oldPermalink"] = $currentPermalink;
                    $values['Permalink'] = AlPageManager::slugify($values["Permalink"]);
                } else {
                    unset($values['Permalink']);
                }
            }

            if (isset($values['MetaTitle']) && $values['MetaTitle'] == $this->alSeo->getMetaTitle()) {
                unset($values['MetaTitle']);
            }

            if (isset($values['MetaDescription']) && $values['MetaDescription'] == $this->alSeo->getMetaDescription()) {
                unset($values['MetaDescription']);
            }

            if (isset($values['MetaKeywords']) && $values['MetaKeywords'] == $this->alSeo->getMetaKeywords()) {
                unset($values['MetaKeywords']);
            }

            $this->validator->checkEmptyParams($values);
            $this->validator->checkOnceValidParamExists(array('Permalink' => '', 'MetaTitle' => '', 'MetaDescription' => '', 'MetaKeywords' => '', 'SitemapChangefreq' => '', 'SitemapPriority' => ''), $values);

            $this->seoRepository->startTransaction();
            $this->seoRepository->setRepositoryObject($this->alSeo);

            $result = (!empty($values)) ? $this->seoRepository->save($values) : true;

            if (false !== $result) {
                $eventName = SeoEvents::BEFORE_EDIT_SEO_COMMIT;
                $result = !$this->eventsHandler
                                ->createEvent($eventName, '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Seo\BeforeEditSeoCommitEvent', array($this, $values))
                                ->dispatch()
                                ->getEvent($eventName)
                                ->isAborted();
            }

            if (false !== $result) {
                $this->seoRepository->commit();

                $this->eventsHandler
                     ->createEvent(SeoEvents::AFTER_EDIT_SEO, '\RedKiteLabs\RedKiteCmsBundle\Core\Event\Content\Seo\AfterSeoEditedEvent', array($this))
                     ->dispatch();

                return $result;
            } 
            
            $this->seoRepository->rollBack();

            return $result;
        } catch (General\EmptyArgumentsException $ex) {
        } catch (General\ArgumentExpectedException $ex) {
        } catch (\Exception $e) {
            if (isset($this->seoRepository) && $this->seoRepository !== null) {
                $this->seoRepository->rollBack();
            }

            throw $e;
        }
    }
}
