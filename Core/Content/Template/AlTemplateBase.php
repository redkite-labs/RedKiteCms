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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;

/**
 * Defines the template content manager object
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
abstract class AlTemplateBase extends AlContentManagerBase
{
    protected $alLanguage; 
    protected $alPage;
    
    /**
     * Contructor
     * 
     * @param ContainerInterface $container
     * @param AlPage $alPage
     * @param AlLanguage $alLanguage 
     */
    public function __construct(ContainerInterface $container, AlPage $alPage = null, AlLanguage $alLanguage = null) 
    {
        parent::__construct($container);
        
        $this->alLanguage = (null !== $alLanguage) ? $alLanguage : $this->container->get('al_page_tree')->getAlLanguage(); 
        $this->alPage = (null !== $alPage) ? $alPage : $this->container->get('al_page_tree')->getAlPage(); 
    }
}