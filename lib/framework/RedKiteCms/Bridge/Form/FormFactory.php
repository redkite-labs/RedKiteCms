<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\Bridge\Form;


use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Content\PageCollection\PagesCollectionParser;

/**
 * The object deputed to handle the Symfony form factory
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Bridge\Form
 */
class FormFactory
{
    /**
     * @type ConfigurationHandler
     */
    private $configurationHandler;
    /**
     * @type \Symfony\Component\Form\FormFactory
     */
    private $formFactory;
    /**
     * @type PagesCollectionParser
     */
    private $pagesParser;

    /**
     * Constructor
     *
     * @param ConfigurationHandler $configurationHandler
     * @param \Symfony\Component\Form\FormFactory $formFactory
     * @param PagesCollectionParser $pagesParser
     */
    public function __construct(
        ConfigurationHandler $configurationHandler,
        \Symfony\Component\Form\FormFactory $formFactory,
        PagesCollectionParser $pagesParser
    ) {
        $this->configurationHandler = $configurationHandler;
        $this->formFactory = $formFactory;
        $this->pagesParser = $pagesParser;
    }

    /**
     * Creates the form
     *
     * @param string $className
     * @param string $username
     * @return \Symfony\Component\Form\FormView
     */
    public function create($className, $username)
    {
        $reflectionClass = new \ReflectionClass($className);

        $permalinks = $this->pagesParser
            ->contributor($username)
            ->parse()
            ->permalinksByLanguage(
                $this->configurationHandler->language() . '_' . $this->configurationHandler->country()
            );
        $permalinksForSelect = (!empty($permalinks)) ? array_combine($permalinks, $permalinks) : array();
        $params = array($permalinksForSelect);

        $form = $this->formFactory->create($reflectionClass->newInstanceArgs($params));

        return $form->createView();
    }
}