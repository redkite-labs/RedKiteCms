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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Propel;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Repository\Repository\ThemeRepositoryInterface;
use AlphaLemon\ThemeEngineBundle\Core\Model\AlThemeQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Exception\Content\General\InvalidParameterTypeException;


/**
 *  Adds some filters to the AlThemeQuery object
 *
 *  @author alphalemon <webmaster@alphalemon.com>
 */
class AlThemeRepositoryPropel extends Base\AlPropelRepository implements ThemeRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getModelObjectClassName()
    {
        return '\AlphaLemon\ThemeEngineBundle\Model\AlTheme';
    }

    /**
     * {@inheritdoc}
     */
    public function setModelObject($object = null)
    {
        if (null !== $object && !$object instanceof AlTheme) {
            throw new InvalidParameterTypeException('AlThemeRepositoryPropel accepts only AlTheme propel objects');
        }

        return parent::setModelObject($object);
    }

    /**
     * {@inheritdoc}
     */
    public function fromName($themeName)
    {
        if (null === $themeName)
        {
            return null;
        }

        if (!is_string($themeName))
        {
            throw new \InvalidArgumentException('The name of the theme must be a string. The theme attribute cannot be retrieved');
        }

        return AlThemeQuery::create()
                    ->filterByThemeName($themeName)
                    ->findOne();
    }

    /**
     * {@inheritdoc}
     */
    public function activeBackend()
    {
        return AlThemeQuery::create()
                    ->filterByActive(1)
                    ->findOne();
    }
}