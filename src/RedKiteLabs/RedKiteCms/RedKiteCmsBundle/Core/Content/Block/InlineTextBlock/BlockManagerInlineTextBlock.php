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

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\InlineTextBlock;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\BlockManager;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\EventsHandler\EventsHandlerInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Validator\ParametersValidatorInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Repository\Factory\FactoryRepositoryInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Translator\TranslatorInterface;

/**
 * BlockManagerInlineTextBlock is the base object deputated to handle an inline editor
 * to manage an hypertext block
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 *
 * @api
 */
abstract class BlockManagerInlineTextBlock extends BlockManager
{
    /** @var null|TranslatorInterface */
    protected $translator;

    /**
     * Constructor
     *
     * @param null|EventsHandlerInterface       $eventsHandler
     * @param null|FactoryRepositoryInterface   $factoryRepository
     * @param null|ParametersValidatorInterface $validator
     * @param null|TranslatorInterface          $translator
     */
    public function __construct(EventsHandlerInterface $eventsHandler = null, FactoryRepositoryInterface $factoryRepository = null, ParametersValidatorInterface $validator = null, TranslatorInterface $translator = null)
    {
        parent::__construct($eventsHandler, $factoryRepository, $validator);

        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public function getDefaultValue()
    {
        $message = "hypertext_block";
        if (null !== $this->translator) {
            $message = $this->translator->translate($message, array(), 'RedKiteCmsBundle');
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
