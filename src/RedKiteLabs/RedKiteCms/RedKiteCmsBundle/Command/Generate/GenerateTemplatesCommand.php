<?php

namespace RedKiteLabs\RedKiteCmsBundle\Command\Generate;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use RedKiteLabs\RedKiteCmsBundle\Core\Generator\AlTemplateGenerator;
use RedKiteLabs\RedKiteCmsBundle\Core\Generator\TemplateParser\AlTemplateParser;
use RedKiteLabs\RedKiteCmsBundle\Core\Generator\AlSlotsGenerator;
use RedKiteLabs\RedKiteCmsBundle\Core\Generator\AlExtensionGenerator;

class GenerateTemplatesCommand extends ContainerAwareCommand
{
    protected $templateParser;
    protected $templateGenerator;
    protected $slotsGenerator;
    protected $extensionGenerator;

    public function setTemplateParser(AlTemplateParser $templateParser)
    {
        $this->templateParser = $templateParser;
    }

    public function getTemplateParser()
    {
        return $this->templateParser;
    }

    public function setTemplateGenerator(AlTemplateGenerator $templateGenerator)
    {
        $this->templateGenerator = $templateGenerator;
    }

    public function getTemplateGenerator()
    {
        return $this->templateGenerator;
    }

    public function setSlotsGenerator(AlSlotsGenerator $slotsGenerator)
    {
        $this->slotsGenerator = $slotsGenerator;
    }

    public function getSlotsGenerator()
    {
        return $this->slotsGenerator;
    }

    public function setExtensionGenerator(AlExtensionGenerator $extensionGenerator)
    {
        $this->extensionGenerator = $extensionGenerator;
    }

    public function getExtensionGenerator()
    {
        return $this->extensionGenerator;
    }

    protected function configure()
    {
        $this
            ->setName('redkitecms:generate:templates')
            ->setDescription('Generate the templates config files for a theme')
            ->setDefinition(array(
                new InputArgument('theme', InputArgument::REQUIRED, 'The name of the theme ou want to parse to generate the templates services'),
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
        $themeName = $input->getArgument('theme');
        $kernel = $this->getContainer()->get('kernel');
        $dir = $kernel->locateResource('@' . $themeName);
        $namespace = $kernel
            ->getBundle($themeName)
            ->getNamespace()
        ;

        // @codeCoverageIgnoreStart
        if (null === $this->templateParser) {
            $this->templateParser = new AlTemplateParser($this->getContainer()->get('templating.locator'), $this->getContainer()->get('templating.name_parser'), $dir . 'Resources/views/Theme', $kernel->getRootDir(), $themeName);
        }

        if (null === $this->templateGenerator) {
            $this->templateGenerator = new AlTemplateGenerator();
        }

        if (null === $this->slotsGenerator) {
            $this->slotsGenerator = new AlSlotsGenerator();
        }

        if (null === $this->extensionGenerator) {
            $this->extensionGenerator = new AlExtensionGenerator();
        }
        // @codeCoverageIgnoreEnd

        //$baseSlots = $slotFiles = array();
        $parsedTemplates = $this->templateParser->parse();
        $this->addOption('template-name', '', InputOption::VALUE_NONE, '');
        $templates = $parsedTemplates["templates"];
        foreach ($templates as $templateAttributes) {
            $templateFileName = $templateAttributes["name"];
            $templateName = basename($templateFileName, '.html.twig');
            $message = $this->templateGenerator->generateTemplate($dir . 'Resources/config/templates', $themeName, $templateName, $templateAttributes["slots"]);
            $output->writeln($message);

            /*
            $slots = $elements['slots'];
            if (empty($slots)) {
                continue;
            }

            $slotFiles[] = $templateName;
            $message = $this->slotsGenerator->generateSlots($dir . 'Resources/config/templates/slots', $themeName, $templateName, $slots);
            $output->writeln($message);*/
        }
        
        $message = $this->slotsGenerator->generateSlots($dir . 'Resources/config/slots', $themeName, $templateName, $parsedTemplates["slots"]);
        $output->writeln($message);
        
/*
        // @codeCoverageIgnoreStart
        if ( ! empty($baseSlots)) {
            $message = $this->slotsGenerator->generateSlots($dir . 'Resources/config/templates/slots', $themeName, 'base', $baseSlots);
            $output->writeln($message);
        }
        // @codeCoverageIgnoreEnd
        */
        $message = $this->extensionGenerator->generateExtension($namespace, $dir . 'DependencyInjection', $themeName, $templates);
        $output->writeln($message);
    }
}
