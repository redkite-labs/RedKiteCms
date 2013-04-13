<?php
/**
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Tests\Integrated\Model\Propel\Base;

use AlphaLemon\AlphaLemonCmsBundle\Tests\WebTestCaseFunctional;
/**
 * BaseModelPropel
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
abstract class BaseModelPropel extends WebTestCaseFunctional
{
    public static function setUpBeforeClass()
    {
        self::$languages = array(
            array('LanguageName'      => 'en'),
            array('LanguageName'      => 'es',)
        );

        self::$pages = array(
            array('PageName'      => 'index',
                  'TemplateName'  => 'home',
                  'IsHome'        => '1',
                  'Permalink'     => 'this is a website fake page',
                  'MetaTitle'         => 'page title',
                  'MetaDescription'   => 'page description',
                  'MetaKeywords'      => 'key'),
            array('PageName'      => 'page1',
                  'TemplateName'  => 'empty',
                  'Permalink'     => 'page-1',
                  'MetaTitle'         => 'page 1 title',
                  'MetaDescription'   => 'page 1 description',
                  'MetaKeywords'      => '')

            );
        self::populateDb();
    }
}