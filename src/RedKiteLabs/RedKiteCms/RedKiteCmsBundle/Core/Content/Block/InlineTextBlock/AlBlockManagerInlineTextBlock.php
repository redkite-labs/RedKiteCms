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

namespace RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\InlineTextBlock;

use RedKiteLabs\RedKiteCmsBundle\Core\Content\Block\AlBlockManager;
use RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Translator\AlTranslatorInterface;

/**
 * AlBlockManagerInlineTextBlock is the base object deputated to handle an inline editor
 * to manage an hypertext block
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
abstract class AlBlockManagerInlineTextBlock extends AlBlockManager
{
    protected $translator;

    /**
     * Constructor
     *
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\EventsHandler\AlEventsHandlerInterface           $eventsHandler
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Repository\Factory\AlFactoryRepositoryInterface  $factoryRepository
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     * @param \RedKiteLabs\RedKiteCmsBundle\Core\Translator\AlTranslatorInterface                 $translator
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
