<?php

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Command\Generate;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Generator\TemplateGenerator;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Generator\TemplateParser\TemplateParser;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Generator\SlotsGenerator;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Generator\ExtensionGenerator;

class GenerateTemplatesCommand extends ContainerAwareCommand
{
    protected $templateParser;
    protected $templateGenerator;
    protected $slotsGenerator;
    protected $extensionGenerator;

    public function setTemplateParser(TemplateParser $templateParser)
    {
        $this->templateParser = $templateParser;
    }

    public function getTemplateParser()
    {
        return $this->templateParser;
    }

    public function setTemplateGenerator(TemplateGenerator $templateGenerator)
    {
        $this->templateGenerator = $templateGenerator;
    }

    public function getTemplateGenerator()
    {
        return $this->templateGenerator;
    }

    public function setSlotsGenerator(SlotsGenerator $slotsGenerator)
    {
        $this->slotsGenerator = $slotsGenerator;
    }

    public function getSlotsGenerator()
    {
        return $this->slotsGenerator;
    }

    public function setExtensionGenerator(ExtensionGenerator $extensionGenerator)
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
            $this->templateParser = new TemplateParser($this->getContainer()->get('templating.locator'), $this->getContainer()->get('templating.name_parser'));
        }

        if (null === $this->templateGenerator) {
            $this->templateGenerator = new TemplateGenerator();
        }

        if (null === $this->slotsGenerator) {
            $this->slotsGenerator = new SlotsGenerator();
        }

        if (null === $this->extensionGenerator) {
            $this->extensionGenerator = new ExtensionGenerator();
        }
        // @codeCoverageIgnoreEnd

        $parsedTemplates = $this->templateParser->parse($dir . 'Resources/views/Theme', $kernel->getRootDir(), $themeName);
        $this->addOption('template-name', '', InputOption::VALUE_NONE, '');
        $templates = $parsedTemplates["templates"];
        foreach ($templates as $templateAttributes) {
            $templateFileName = $templateAttributes["name"];
            $templateName = basename($templateFileName, '.html.twig');
            $message = $this->templateGenerator->generateTemplate($dir . 'Resources/config/templates', $themeName, $templateName, $templateAttributes["slots"]);
            $output->writeln($message);
        }

        $message = $this->slotsGenerator->generateSlots($dir . 'Resources/config/slots', $themeName, $parsedTemplates["slots"]);
        $output->writeln($message);

        $message = $this->extensionGenerator->generateExtension($namespace, $dir . 'DependencyInjection', $themeName, $templates);
        $output->writeln($message);
    }
}
