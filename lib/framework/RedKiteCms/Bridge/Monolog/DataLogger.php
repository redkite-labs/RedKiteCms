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

namespace RedKiteCms\Bridge\Monolog;

use Psr\Log\LoggerInterface;

/**
 * This object statically handles the MonoLogger logger
 *
 * @author  RedKite Labs <webmaster@redkite-labs.com>
 * @package RedKiteCms\Bridge\Monolog
 */
class DataLogger
{
    const INFO = "info";
    const DEBUG = "debug";
    const NOTICE = "notice";
    const WARNING = "warning";
    const ERROR = "error";
    const CRITICAL = "critical";
    const ALERT = "alert";
    const EMERGENCY = "emergency";

    /**
     * @type \Psr\Log\LoggerInterface
     */
    private static $logger = null;

    /**
     * Injects the logger
     *
     * @param LoggerInterface $logger
     */
    public static function init(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    /**
     * Logs the given message
     *
     * @param        $message
     * @param string $type
     */
    public static function log($message, $type = DataLogger::INFO)
    {
        if (null === self::$logger) {
            return;
        }

        if (!method_exists(self::$logger, $type)) {
            $exceptionMessage = sprintf('Logger does not support the %s method.', $type);

            throw new \InvalidArgumentException($exceptionMessage);
        }

        self::$logger->$type($message);
    }
}