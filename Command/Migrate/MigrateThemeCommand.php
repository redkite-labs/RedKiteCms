<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Command\Migrate;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Upgrades to AlphaLemonCms Beta4 release
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class MigrateThemeCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDescription('Migrates a RedKite CMS theme from RedKite CMS 1.1.0 RC or previous to RedKite CMS 1.1.0 stable')
            ->setDefinition(array(
                new InputArgument('theme', InputArgument::REQUIRED, 'The name of the theme you want to migrate'),
            ))
            ->setName('redkitecms:migrate:theme');
    }

    /**
     * @see Command
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $themeName = $input->getArgument('theme');
        $kernel = $this->getContainer()->get('kernel');
        $themePath = $kernel->locateResource('@' . $themeName);
        
        $slots = array();
        $finder = new \Symfony\Component\Finder\Finder();
        $templateFiles = $finder->files('*.twig')->in($themePath  . '/Resources/views/Theme');
        foreach ($templateFiles as $template) {
            $template = (string) $template;
            $templateContents = file_get_contents($template);
            
            $currentTemplateContents = $templateContents;
            preg_match_all('/([^%]+)[^\w]+BEGIN-SLOT.*?\{% endblock[^%\}]+%\}/si', $templateContents, $matches, PREG_SET_ORDER);
            foreach($matches as $match) {
                $block = '{%' . $match[0];
                $blockName = trim(str_replace('block ', '', $match[1]));
                $currentTemplateContents = str_replace($block, "{{ block('$blockName') }}", $currentTemplateContents);
                
                preg_match('/([\r\n][\s]+)\{# BEGIN-SLOT/', $block, $spacesMatch);
                if (array_key_exists(1, $spacesMatch)) {
                    $block = str_replace($spacesMatch[1], "\n    ", $block);
                    $block = preg_replace('/([\r\n][\s]+)\{% endblock/', "\n{% endblock", $block);
                }               
                $slots[] = $block;
            }
            
            $useStatement = "{% use '$themeName:Slots:slots.html.twig' %}\n";
            preg_match('/\{% extend[^\}]+\}/', $currentTemplateContents, $m);
            $currentTemplateContents = (array_key_exists(0, $m)) ? str_replace($m[0], $m[0] . "\n\n" . $useStatement, $currentTemplateContents) : $currentTemplateContents = $useStatement . $currentTemplateContents;
            file_put_contents($template, $currentTemplateContents);
        }
        
        $slotsDir = $themePath  . '/Resources/views/Slots';
        @mkdir($slotsDir);
        file_put_contents($slotsDir . '/slots.html.twig', implode("\n\n", $slots));
    }
}
