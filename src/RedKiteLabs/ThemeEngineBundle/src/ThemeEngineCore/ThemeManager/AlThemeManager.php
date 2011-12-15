<?php
/*
 * This file is part of the AlphaLemonThemeEngineBundle and it is distributed
 * under the MIT License. In addiction, to use this bundle, you must leave
 * intact this copyright notice.
 *
 * (c) Since 2011 AlphaLemon
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://alphalemon.com
 * 
 * @license    MIT License
 */

namespace ThemeEngineCore\ThemeManager;

use Symfony\Bundle\FrameworkBundle\Util\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\ThemeEngineBundle\Model\AlTheme;
use ThemeEngineCore\Model\AlThemeQuery;
use PageTreeCore\Tools\AlToolkit;

/**
 * Implements the AlThemeManagerInterface to manage a theme
 * 
 * @author AlphaLemon
 */
class AlThemeManager implements AlThemeManagerInterface
{
    protected $container;
    protected $connection;
    protected $theme = null;
    protected $siteThemesDir;
    protected $defaultThemesDir;
    protected $themes = array();

    /**
     * Contructor
     *
     * @param string  The path to the themes folder. When null the default theme's folder is used
     *
     */
    public function __construct(ContainerInterface $container, $siteThemesDir = null, $defaultThemesDir = null)
    {
        $this->container = $container;
        $this->connection =  \Propel::getConnection();
        $this->siteThemesDir = $siteThemesDir;
        $this->defaultThemesDir = $defaultThemesDir;
    }

    /**
     * {@inheritdoc}
     */
    public function add(array $values = array())
    {
        if(empty($values))
        {
            throw new \InvalidArgumentException("You must provide at least a valid option to add a theme");
        }
        
        if(!array_key_exists('name', $values))
        {
            throw new \InvalidArgumentException("The name option is mandatory to add a new theme");
        }
        
        if(AlThemeQuery::create()->filterByThemeName($values['name'])->count() > 0)
        {
            throw new \RuntimeException("The theme you are trying to add already exists");
        }
        
        try
        {
            $rollback = false;
            $this->connection->beginTransaction();
            
            $theme = new AlTheme();
            $theme->setThemeName($values['name']);            
            $result = $theme->save();
            if ($theme->isModified() && $result == 0) $rollback = true;

            if(array_key_exists('active', $values) && $values['active'] == true) $this->activate($values['name']);
            
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
            if(AlThemeQuery::create()->filterByThemeName($themeName)->count() == 0)
            {
                throw new \InvalidArgumentException("The theme you are trying to activate does not exist");
            }
                    
            // The current theme is already the one active: skip
            $theme = AlThemeQuery::create()->filterByActive(1)->findOne();
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
                    $theme = AlThemeQuery::create()->filterByThemeName($themeName)->findOne();
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
                
            $theme = AlThemeQuery::create()->filterByThemeName($themeName);
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
