<?php

namespace AlphaLemon\ThemeEngineBundle\Command\Generate;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AlphaLemon\ThemeEngineBundle\Core\Generator\AlTemplateGenerator;
use Symfony\Component\DependencyInjection\Container;
use Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper;

class GenerateTemplatesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('alphalemon:generate:templates')
            ->setDescription('Generate a App-Block bundle')
            ->setDefinition(array(
                new InputOption('theme-name', '', InputOption::VALUE_REQUIRED, 'The name of the theme bundle which gets the template'),
            ));
    }

    /**
     * @see Command
     *
     * @throws \InvalidArgumentException When namespace doesn't end with Bundle
     * @throws \RuntimeException         When bundle can't be executed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $themeName = $input->getOption('theme-name');
        $kernel = $this->getContainer()->get('kernel');
        $dir = $kernel->locateResource('@' . $themeName);

        $templateParser = new \AlphaLemon\ThemeEngineBundle\Core\Generator\TemplateParser\AlTemplateParser($dir . 'Resources/views');
        $templates = $templateParser->parse();
        $this->addOption('template-name', '', InputOption::VALUE_NONE, '');
        foreach ($templates as $templateName => $elements) {
            $templateName = basename($templateName, '.html.twig');
            if ($templateName !== 'base') {
                $generator = new AlTemplateGenerator();
                $message = $generator->generateTemplate($dir . 'Resources/config/templates', $themeName, $templateName, $elements['assets']);
                $output->writeln($message);
            }

            $slots = $elements['slots'];
            if (!empty($slots)) {
                $slotsGenerator = new \AlphaLemon\ThemeEngineBundle\Core\Generator\AlSlotsGenerator();
                $message = $slotsGenerator->generateSlots($dir . 'Resources/config/templates/slots', $themeName, $templateName, $slots);

                $output->writeln($message);
            }
        }
    }
}