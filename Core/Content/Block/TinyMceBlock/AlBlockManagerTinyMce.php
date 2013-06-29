<?php
/**
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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\TinyMceBlock;

use AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Deprecated\AlphaLemonDeprecatedException;

/**
 * AlBlockManagerTinyMce provides support for TinyMce library
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * @deprecated since 1.1.0
 * @codeCoverageIgnore
 */
abstract class AlBlockManagerTinyMce extends AlBlockManager
{
    protected $urlManager;

    /**
     * Constructor
     *
     * @param EventDispatcherInterface       $dispatcher
     * @param AlUrlManagerInterface          $urlManager
     * @param AlFactoryRepositoryInterface   $factoryRepository
     * @param AlParametersValidatorInterface $validator
     */
    public function __construct(AlEventsHandlerInterface $eventsHandler, AlUrlManagerInterface $urlManager, AlFactoryRepositoryInterface $factoryRepository = null, AlParametersValidatorInterface $validator = null)
    {
        throw new AlphaLemonDeprecatedException("AlBlockManagerTinyMce has been deprecated since AlphaLemon 1.1.0");
        
        parent::__construct($eventsHandler, $factoryRepository, $validator);

        $this->urlManager = $urlManager;
    }

    /**
     * {@inheritdoc}
     *
     * Extends the base edit method to normalize the urls found in the given html code
     */
    protected function edit(array $values)
    {
        if (array_key_exists('Content', $values)) {
            $urlManager = $this->urlManager;
            $values['Content'] = preg_replace_callback('/(\<a[^\>]+href[="\'\s]+)([^"\'\s]+)?([^\>]+\>)/s', function ($matches) use ($urlManager) {

                $url = $urlManager
                        ->fromUrl($matches[2])
                        ->getInternalUrl();

                if(null === $url) $url = $matches[2];

                return $matches[1] . $url . $matches[3];
            }, $values['Content']);
        }

        return parent::edit($values);
    }
}
