<?php
/*
 * This file is part of the AlphaLemonCMS InstallerBundle and it is distributed
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

namespace AlphaLemon\CmsInstallerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

use AlphaLemon\ThemeEngineBundle\Core\ThemeManager\AlThemeManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Language\AlLanguageManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Page\AlPageManager;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlUser;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlRole;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Validator;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\PageBlocks\AlPageBlocks;
use AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\AlTwigDeployer;

/**
 * Populates the database after a fresh install
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class PopulateCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDescription('Populates the database with default values. Be careful if you try to run this command on an existind database, because it is resets and repopulates the database itself')
            ->setDefinition(array(
                new InputArgument('dsn', InputArgument::REQUIRED, 'The dsn to connect the database'),
                new InputOption('user', '', InputOption::VALUE_OPTIONAL, 'The database user', 'root'),
                new InputOption('password', null, InputOption::VALUE_OPTIONAL, 'The database password', ''),
            ))
            ->setName('alphalemon:populate');
    }

    /**
     * @see Command
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = new \PropelPDO($input->getArgument('dsn'), $input->getOption('user'), $input->getOption('password'));

        $queries = array(
            'TRUNCATE al_block;',
            'TRUNCATE al_language;',
            'TRUNCATE al_page;',
            'TRUNCATE al_page_attribute;',
            'TRUNCATE al_user;',
            'TRUNCATE al_role;',
            'INSERT INTO al_language (language) VALUES(\'-\');',
            'INSERT INTO al_page (page_name) VALUES(\'-\');',
        );

        foreach($queries as $query)
        {
            $statement = $connection->prepare($query);
            $statement->execute();
        }

        /* TODO Users management

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
        $user->setEmail('');
        $user->save();
        */

        $factoryRepository = $this->getContainer()->get('alphalemon_cms.factory_repository');
        $languageManager = new AlLanguageManager($this->getContainer()->get('alpha_lemon_cms.events_handler'), $factoryRepository, new Validator\AlParametersValidatorLanguageManager($factoryRepository));
        $languageManager->set(null)->save(
            array(
                'Language'      => 'en',
            ));

        $themes = $this->getContainer()->get('alphalemon_theme_engine.themes');
        $theme = $themes->getTheme('BusinessWebsiteThemeBundle');
        $template = $theme->getTemplate('home');

        $pageContentsContainer = new AlPageBlocks($factoryRepository);
        $templateManager = new AlTemplateManager($this->getContainer()->get('alpha_lemon_cms.events_handler'), $factoryRepository, $template, $pageContentsContainer, $this->getContainer()->get('alphalemon_cms.block_manager_factory'));
        $templateManager->refresh();

        $pageManager = new AlPageManager($this->getContainer()->get('alpha_lemon_cms.events_handler'), $templateManager, $factoryRepository, new Validator\AlParametersValidatorPageManager($factoryRepository));
        $pageManager->set(null)->save(
            array(
                'PageName' => 'index',
                'TemplateName' => 'home',
                'Permalink' => 'homepage',
                'MetaTitle' => 'A website made with AlphaLemon CMS',
                'MetaDescription' => 'Website homepage',
                'MetaKeywords' => '',
            ));

        try
        {
            $deployer = new AlTwigDeployer($this->getContainer());
            $deployer->deploy();
        }
        catch(\Exception $ex)
        {
            echo $ex->getMessage();
        }
    }
}
