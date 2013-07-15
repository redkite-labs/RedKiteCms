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

namespace AlphaLemon\CmsInstallerBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use AlphaLemon\CmsInstallerBundle\Core\BowerBuilder\AlBowerBuilder;

/**
 * Builds the bower component.json file
 *
 * @author alphalemon <webmaster@alphalemon.com>
 */
class BowerBuildCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDescription('Builds the bower component.json file')->setDefinition(array(
                new InputOption('web-folder', '', InputOption::VALUE_OPTIONAL, 'The web folder of your application', 'web'),
            ))
            ->setName('alphalemon:build:bower');
    }

    /**
     * @see Command
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $bower = new AlBowerBuilder($container->get('kernel'));
        $bower->build($input->getOption('web-folder') . '/component.json');
        
        $output->writeln("Bower component.json file has been generated");
    }
}
