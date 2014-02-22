<?php
/*
 * This file is part of the RedKite CMS InstallerBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKiteCms <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT LICENSE
 *
 */

namespace RedKiteCms\InstallerBundle\Core\Installer\DbBootstrapper\Base;

use Symfony\Component\DependencyInjection\ContainerInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Repository\Propel\Base\AlPropelOrm;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Language\AlLanguageManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Page\AlPageManager;
use RedKiteLabs\RedKiteCmsBundle\Model\AlUser;
use RedKiteLabs\RedKiteCmsBundle\Model\AlRole;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Validator;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\Template\AlTemplateManager;
use RedKiteLabs\RedKiteCmsBundle\Core\Content\PageBlocks\AlPageBlocks;
use RedKiteLabs\RedKiteCmsBundle\Core\Deploy\AlTwigDeployer;

/**
 * Implements the object deputated to boostrap the database
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
abstract class BaseDbBootstrapper 
{
    /**
     * Contructor
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $options
     */
    public function __construct(ContainerInterface $container, $vendorDir)
    {
        $this->container = $container;
        $this->host = $this->container->getParameter('rkcms_database_host');
        $this->driver =  $this->container->getParameter('rkcms_database_driver');
        $this->port =  $this->container->getParameter('rkcms_database_port');
        $this->database =  $this->container->getParameter('rkcms_database_name');
        $this->user =  $this->container->getParameter('rkcms_database_user');
        $this->password =  $this->container->getParameter('rkcms_database_password');
        
        $options = array(
            'host' =>  $this->host,
            'driver' =>  $this->driver,
            'port' =>  $this->port,
            'database' =>  $this->database,
            'user' =>  $this->user,
            'password' =>  $this->password,
        );
        
        $dsnBuilderClassName = '\RedKiteCms\InstallerBundle\Core\DsnBuilder\GenericDsnBuilder';
        $specificDsnBuilderClassName = '\RedKiteCms\InstallerBundle\Core\DsnBuilder\\' . ucfirst($this->driver) . 'DsnBuilder';
        if (class_exists($specificDsnBuilderClassName)) {
            $dsnBuilderClassName = $specificDsnBuilderClassName;
        }
        $this->dsnBuilder = new $dsnBuilderClassName($options);
    }
    
    /**
     * Bootstraps the database
     */
    public function bootstrap()
    {
        $this->factoryReporsitory = $this->container->get('red_kite_cms.factory_repository');
        $this->themes = $this->container->get('red_kite_labs_theme_engine.themes');
        $this->eventsHandler = $this->container->get('red_kite_cms.events_handler');
        $this->blockManagerFactory = $this->container->get('red_kite_cms.block_manager_factory');
        $this->siteBootstrap = $this->container->get('red_kite_cms.site_bootstrap');
        $this->activeTheme = $this->container->get('red_kite_cms.active_theme');
        
        $language = new \RedKiteLabs\RedKiteCmsBundle\Model\AlLanguage();
        $language->setLanguageName('-');
        $language->save();
                
        $language = new \RedKiteLabs\RedKiteCmsBundle\Model\AlPage();
        $language->setPageName('-');
        $language->save();
        
        $language = new \RedKiteLabs\RedKiteCmsBundle\Model\AlConfiguration();
        $language->setParameter('language');        
        $language->setValue('en');
        $language->save();
        
        $adminRoleId = 0;
        $roles = array('ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN');
        foreach ($roles as $role) {
            $alRole = new AlRole();
            $alRole->setRole($role);
            $alRole->save();

            if($role =='ROLE_ADMIN') $adminRoleId = $alRole->getId();
        }

        $user = new AlUser();
        $encoder = new MessageDigestPasswordEncoder();
        $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $password = $encoder->encodePassword('admin', $salt);

        $user->setSalt($salt);
        $user->setPassword($password);
        $user->setRoleId($adminRoleId);
        $user->setUsername('admin');
        $user->setEmail('user@aserver.com');
        $user->save();
        
        $bundles = $this->container->getParameter('kernel.bundles');
        $themeName = $this->container->getParameter('red_kite_cms_installer.default_theme');
        if (!array_key_exists($themeName, $bundles)) {
            throw new \RuntimeException(sprintf("It seems the %s theme is not registered as valid bundle. Please check your configuration.", $themeName));
        }
        
        $factoryRepository = $this->factoryReporsitory;
        $theme = $this->themes->getTheme($themeName);
        $template = $theme->getTemplate('home');

        $pageBlocks = new AlPageBlocks($factoryRepository);
        $templateManager = new AlTemplateManager($this->eventsHandler, $factoryRepository, $this->blockManagerFactory);
        $templateManager->refresh($theme->getThemeSlots(), $template, $pageBlocks);
        
        $languageManager = new AlLanguageManager($this->eventsHandler, $factoryRepository, new Validator\AlParametersValidatorLanguageManager($factoryRepository));
        $pageManager = new AlPageManager($this->eventsHandler, $templateManager, $factoryRepository, new Validator\AlParametersValidatorPageManager($factoryRepository));
        $siteBootstrap = $this->siteBootstrap;        
        $result = $siteBootstrap
                    ->setLanguageManager($languageManager)
                    ->setPageManager($pageManager)
                    ->setTemplateManager($templateManager)
                    ->bootstrap();
        
        if ( ! $result) {
            return $siteBootstrap->getErrorMessage();
        }
        
        $this->activeTheme->writeActiveTheme($themeName);
        
        try
        {
            $twigTemplateWrites = $this->container->get('red_kite_cms.twig_template_writer');
            $routingGenerator = $this->container->get('red_kite_cms.routing_generator_production');
            $deployer = new AlTwigDeployer($twigTemplateWrites, $routingGenerator);
            $deployer->deploy();
        }
        catch(\Exception $ex)
        {
            echo $ex->getMessage();
        }
        
        return true;
    }

    /**
     * Creates the database
     */
    public function createDatabase()
    {
        $orm = $this->setUpOrm($this->dsnBuilder->getBaseDsn());

        $query = 'DROP DATABASE IF EXISTS ' . $this->database;
        $result = $orm->executeQuery($query);

        $query = 'CREATE DATABASE ' . $this->database;
        $result = $orm->executeQuery($query);

        if ( ! $result) {
            throw new \RuntimeException(sprintf("I'm not able to create the %s database. Please create it manually or choose another one.", $this->database));
        }
    }
    
    protected function setUpOrm($dsn)
    {
        $connection = new \PropelPDO($dsn, $this->user, $this->password);            
            
        return new AlPropelOrm($connection);
    }
}
