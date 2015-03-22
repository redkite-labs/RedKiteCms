<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <info@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteCms\FilesystemEntity;

use JMS\Serializer\SerializerInterface;
use RedKiteCms\Tools\FilesystemTools;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FilesystemEntity defines the base methods for a filesystem entity
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\FilesystemEntity
 */
abstract class FilesystemEntity implements FilesystemEntityInterface
{
    /**
     * @type \JMS\Serializer\SerializerInterface
     */
    protected $serializer;
    /**
     * @type \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected $optionsResolver;
    /**
     * @type string
     */
    protected $workMode;
    /**
     * @type string
     */
    protected $productionDir;
    /**
     * @type string
     */
    protected $contributorDir;
    /**
     * @type array
     */
    protected $options = array();

    /**
     * @param \JMS\Serializer\SerializerInterface $serializer
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $optionsResolver
     */
    public function __construct(SerializerInterface $serializer, OptionsResolver $optionsResolver)
    {
        $this->serializer = $serializer;
        $this->optionsResolver = $optionsResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function init($sourceDir, array $options, $username = null)
    {
        $this->resolveOptions($options);
        $this->options = $options;

        $path = $this->getBaseDir($sourceDir);
        if (null === $path) {
            return $this;
        }

        $this->workMode = "contributor";
        $this->productionDir = $this->initProductionDir($path);
        if (null === $username) {
            $this->workMode = "production";

            return $this;
        }

        $this->contributorDir = $this->workDir = $this->initContributorDir($path, $username);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductionDir()
    {
        return $this->productionDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getContributorDir()
    {
        return $this->contributorDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getArchiveDir($targetDir = null)
    {
        $targetDir = $this->workDirectory($targetDir);

        return $targetDir . '/archive';
    }

    /**
     * {@inheritdoc}
     */
    public function getDirInUse()
    {
        switch ($this->workMode) {
            case "contributor":
                return $this->contributorDir;

            case "production":
                return $this->productionDir;

            default:
                return null;
        }
    }

    /**
     * Configures the base options for the resolver
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    protected function resolveOptions(array $options)
    {
        $this->optionsResolver->setRequired(
            array(
                'page',
                'language',
                'slot',
                'country',
            )
        );

        $this->optionsResolver->resolve($options);
    }

    /**
     * Returns the work directory
     * @param null|string$targetDir
     *
     * @return null|string
     */
    protected function workDirectory($targetDir)
    {
        if (null === $targetDir) {
            $targetDir = $this->getDirInUse();
        }

        return $targetDir;
    }

    private function getBaseDir($sourceDir)
    {
        return FilesystemTools::slotDir($sourceDir, $this->options);
    }

    private function initProductionDir($baseDir)
    {
        return $baseDir . '/active';
    }

    private function initContributorDir($baseDir, $username)
    {
        if (null === $username) {
            return null;
        }

        return $baseDir . '/contributors/' . $username;
    }
}