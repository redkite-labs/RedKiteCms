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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\InlineTextBlock;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Translator\AlTranslatorInterface;

/**
 * AlBlockManagerInlineTextBlock is the base object deputated to handle an inline editor
 * to manage an hypertext block
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
abstract class AlBlockManagerInlineTextBlock extends AlBlockManager
{
    protected $translator;
    
    /**
     * Constructor
     * 
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\EventsHandler\AlEventsHandlerInterface $eventsHandler
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface $factoryRepository
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Translator\AlTranslatorInterface $translator
     */
    public function __construct(AlEventsHandlerInterface $eventsHandler = null, AlFactoryRepositoryInterface $factoryRepository = null, AlParametersValidatorInterface $validator = null, AlTranslatorInterface $translator = null)
    {
        parent::__construct($eventsHandler, $factoryRepository, $validator);
        
        $this->translator = $translator;
    }
    
    /**
     * @inheritdoc
     */
    public function getDefaultValue()
    {
        $message = "This is the default content for a new hypertext block";  
        if (null !== $this->translator) {
            $message = $this->translator->translate($message);
        }
        
        return array(
            'Content' => $message,
        );
    }
    
    /**
     * @inheritdoc
     */
    protected function editInline()
    {
        return true;
    }
}
