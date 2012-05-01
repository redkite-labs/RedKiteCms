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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Theme;

use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlTheme;
use AlphaLemon\ThemeEngineBundle\Core\Model\AlThemeQuery;
use Symfony\Bundle\FrameworkBundle\Util\Filesystem;
use Symfony\Component\DependencyInjection\Exception;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;
use AlphaLemon\ThemeEngineBundle\Core\Interfaces\AlThemeManagerInterface;

/**
 * Implements the AlThemeManagerInterface to manage a theme
 * 
 * @author alphalemon <webmaster@alphalemon.com>
 */
class AlThemeManager extends AlContentManagerBase implements AlThemeManagerInterface
{
    protected $theme = null;
    protected $siteThemesDir;
    protected $defaultThemesDir;
    protected $themes = array();

    /**
     * Contructor
     *
     * @param string  The path to the themes folder. When null the default theme's folder is used
     */
    public function __construct($container, $siteThemesDir = null, $defaultThemesDir = null)
    {
        parent::__construct($container);

        $this->siteThemesDir = $siteThemesDir;
        $this->defaultThemesDir = $defaultThemesDir;
    }

    /**
     * {@inheritdoc}
     */
    public function add($themeName, array $values = array())
    {
        try
        {
            $rollback = false;
            $this->connection->beginTransaction();

            $theme = new AlTheme();
            $theme->setThemeName($themeName);
            if(array_key_exists('active_backend', $values)) $theme->setActive($values['active_backend']);
            $result = $theme->save();
            if ($theme->isModified() && $result == 0) $rollback = true;

            if (!$rollback)
            {
                $this->connection->commit();
                return true;
            }
            else
            {
                $this->connection->rollback();
                return false;
            }
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function activate($themeName)
    {
        try
        {
            // The current theme is already the one active: skip
            $theme = AlThemeQuery::create()->setContainer($this->container)->activeBackend()->findOne();
            if(null === $theme || $theme->getThemeName() != $themeName)
            {
                $rollback = false;
                $this->connection->beginTransaction();

                // Resets the current active theme
                if(null !== $theme)
                {
                    $theme->setActive(0)->save();
                    if ($theme->isModified() && $result == 0) $rollback = true;
                }
                
                if(!$rollback)
                {
                    // Activates the new one
                    $theme = AlThemeQuery::create()->setContainer($this->container)->fromName($themeName)->findOne();
                    if($theme)
                    {
                        $theme->setActive(1);
                        $result = $theme->save();
                        if ($theme->isModified() && $result == 0) $rollback = true;
                    }
                    else
                    {
                        $rollback = true;
                    }
                }
                
                if (!$rollback)
                {
                    $this->connection->commit();
                    return true;
                }
                else
                {
                    $this->connection->rollBack();
                    return false;
                }
            }
            
            return null;            
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }

    
    /**
     * {@inheritdoc}
     */
    public function remove($themeName)
    {
        try 
        {   
            $this->connection->beginTransaction();
                
            $theme = AlThemeQuery::create()->setContainer($this->container)->fromName($themeName);
            if (null !== $theme)
            {
                $theme->delete();
            }
            
            $this->connection->commit();
            return true;
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }
}
