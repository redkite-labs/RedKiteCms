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

namespace RedKiteLabs\RedKiteCms\InstallerBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use RedKiteLabs\RedKiteCms\InstallerBundle\Core\BowerBuilder\BowerBuilder;

/**
 * Builds the bower component.json file
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
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
            ->setName('redkitecms:build:bower');
    }

    /**
     * @see Command
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $bower = new BowerBuilder($container->get('kernel'));
        $bower->build($input->getOption('web-folder'));
        
        $output->writeln("Bower bower.json and .bowerrc files have been generated");
    }
}
