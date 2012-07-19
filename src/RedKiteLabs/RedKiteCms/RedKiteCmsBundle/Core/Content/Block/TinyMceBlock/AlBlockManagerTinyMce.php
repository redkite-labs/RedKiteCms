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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\TinyMceBlock;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManagerInterface;

/**
 * AlBlockManagerTinyMce provides support for TinyMce library
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class AlBlockManagerTinyMce extends AlBlockManager
{
    protected $urlManager;

    /**
     * Constructor
     * 
     * @param EventDispatcherInterface $dispatcher
     * @param AlFactoryRepositoryInterface $factoryRepository
     * @param AlUrlManagerInterface $urlManager
     * @param AlParametersValidatorInterface $validator 
     */
    public function __construct(EventDispatcherInterface $dispatcher, AlFactoryRepositoryInterface $factoryRepository, AlUrlManagerInterface $urlManager, AlParametersValidatorInterface $validator = null)
    {
        parent::__construct($dispatcher, $factoryRepository, $validator);

        $this->urlManager = $urlManager;
    }

    /**
     * {@inheritdoc}
     * 
     * Extends the base edit method to normalize the urls found in the given html code
     */
    protected function edit(array $values)
    {
        if (array_key_exists('HtmlContent', $values)) {
            $urlManager = $this->urlManager;
            $values['HtmlContent'] = preg_replace_callback('/(\<a[^\>]+href[="\'\s]+)([^"\'\s]+)?([^\>]+\>)/s', function ($matches) use ($urlManager) {

                $url = $urlManager
                        ->fromUrl($matches[2])
                        ->getInternalUrl();
                
                if(null === $url) $url = $matches[2];

                return $matches[1] . $url . $matches[3];
            }, $values['HtmlContent']);
        }

        return parent::edit($values);
    }
}
