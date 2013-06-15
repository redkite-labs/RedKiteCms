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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface;

/**
 * AlBlockManagerJsonBlockCollection is the base object deputated to handle a json content
 * which defines a collection of objects
 *
 * @author alphalemon <webmaster@alphalemon.com>
 *
 * @api
 */
abstract class AlBlockManagerJsonBlockCollection extends AlBlockManagerJsonBase
{
    protected $container;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface                             $container
     * @param \AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator\AlParametersValidatorInterface $validator
     *
     * @api
     */
    public function __construct(ContainerInterface $container, AlParametersValidatorInterface $validator = null)
    {
        $this->container = $container;
        $eventsHandler = $container->get('alpha_lemon_cms.events_handler');
        $factoryRepository = $container->get('alpha_lemon_cms.factory_repository');

        parent::__construct($eventsHandler, $factoryRepository, $validator);
    }

    /**
     * {@inheritdoc}
     *
     * Extends the base edit method to manage a json collection of objects
     *
     * @api
     */
    protected function edit(array $values)
    {
        $values = $this->manageCollection($values);

        return parent::edit($values);
    }

    /**
     * Manages the json collection, adding and removing items collection from the json
     * block
     *
     * @param  array $values
     * @return array
     */
    protected function manageCollection(array $values)
    {
        if (array_key_exists('Content', $values)) {
            $data = json_decode($values['Content'], true);
            $savedValues = $this->decodeJsonContent($this->alBlock);

            if ($data["operation"] == "add") {
                $savedValues[] = $data["value"];
                $values = array("Content" => json_encode($savedValues));
            }

            if ($data["operation"] == "remove") {
                unset($savedValues[$data["item"]]);

                $blocksRepository = $this->container->get('alpha_lemon_cms.factory_repository');
                $repository = $blocksRepository->createRepository('Block');
                $repository->deleteIncludedBlocks($data["slotName"]);

                $values = array("Content" => json_encode($savedValues));
            }
        }

        return $values;
    }
}
