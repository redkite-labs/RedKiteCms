<?php

namespace RedKiteLabs\RedKiteCmsBundle\Core\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\DependencyInjection\Container;

/**
 * AlSlotsGenerator generates the slots file for the given template
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class AlSlotsGenerator extends Generator
{
    protected $themeSkeletonDir;

    /**
     * Constructor
     *
     * @param string $themeSkeletonDir
     */
    public function __construct($themeSkeletonDir = null)
    {
        $this->themeSkeletonDir = (null === $themeSkeletonDir) ? __DIR__ . '/../../Resources/skeleton/app-theme' : $themeSkeletonDir;
    }

    /**
     * Generates the slot file
     *
     * @param  string $dir          The directory where the generated file must be saved
     * @param  string $themeName
     * @param  string $templateName
     * @param  array  $slots
     * @return string A message formatted to be displayed on the console
     */
    public function generateSlots($dir, $themeName, $templateName, array $slots)
    {
        $themeBasename = str_replace('Bundle', '', $themeName);
        $extensionAlias = Container::underscore($themeBasename);

        $parameters = array(
            'theme_name' => $extensionAlias,
            'template_name' => $templateName,
            "slots" => $slots,
        );

        $slotFile = $templateName . '.xml';
        $this->setSkeletonDirs($this->themeSkeletonDir);
        $this->renderFile('slots.xml', $dir . '/' . $slotFile, $parameters);

        $message = '';
        foreach ($slots as $slotName => $slot) {
            if (array_key_exists('errors', $slot)) {
                foreach ($slot['errors'] as $error) {
                    $message .= sprintf('<error>The argument %s assigned to the %s slot is not recognized</error>', $error, $slotName);
                }
            }
        }
        $message .= sprintf('The template\'s slots <info>%s</info> has been generated into <info>%s</info>', $slotFile, $dir);

        return $message;
    }
}
