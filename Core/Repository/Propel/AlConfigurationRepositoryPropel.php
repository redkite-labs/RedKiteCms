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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlConfiguration;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlConfigurationQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\ConfigurationRepositoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidArgumentTypeException;

/**
 *  Implements the BlockRepositoryInterface to work with Propel
 *
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlConfigurationRepositoryPropel extends Base\AlPropelRepository implements ConfigurationRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRepositoryObjectClassName()
    {
        return '\AlphaLemon\AlphaLemonCmsBundle\Model\AlConfiguration';
    }

    /**
     * {@inheritdoc}
     */
    public function setRepositoryObject($object = null)
    {
        if (null !== $object && !$object instanceof AlConfiguration) {
            throw new InvalidArgumentTypeException('AlConfigurationRepositoryPropel accepts only AlConfiguration propel objects');
        }

        return parent::setRepositoryObject($object);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchParameter($parameter)
    {
        return AlConfigurationQuery::create()
            ->filterByParameter($parameter)
            ->findOne();
    }
}
