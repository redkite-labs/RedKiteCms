<?php
/**
 * Created by PhpStorm.
 * User: alphalemon
 * Date: 18/04/15
 * Time: 17.39
 */

namespace RedKiteCms\Rendering\Queue;


use RedKiteCms\Action\FactoryAction;
use RedKiteCms\Configuration\ConfigurationHandler;
use RedKiteCms\Tools\FilesystemTools;

class QueueManager
{
    private $configurationHandler;
    private $factoryAction;
    private $twig;
    private $queue = array();
    private $queueFile = "";
    private $queueExists = false;

    public function __construct(ConfigurationHandler $configurationHandler, FactoryAction $factoryAction, \Twig_Environment $twig)
    {
        $this->configurationHandler = $configurationHandler;
        $this->factoryAction = $factoryAction;
        $this->twig = $twig;
        $this->queueFile = $this->configurationHandler->siteDir() . '/queue/queue.json';
        $this->checkForExistingQueue();
    }

    /**
     * @return boolean
     */
    public function hasQueue()
    {
        return $this->queueExists;
    }

    public function queue($queue)
    {
        $this->queue = array("queue" => $queue);
    }

    function execute($username = null)
    {
        if (!array_key_exists("queue", $this->queue)) {
            return true;
        }

        foreach($this->queue["queue"] as $key => $operation) {
            $action = $this->factoryAction->create($operation["entity"], $operation["action"]);
            if (null === $action) {
                continue;
            }

            try
            {
                $action->execute($operation, $username);
            }
            catch(\Exception $ex) {
                $this->saveQueueFile($ex->getMessage());

                return false;
            }

            unset($this->queue[$key]);
        }

        if ($this->queueExists) {
            $this->removeQueueFile();
        }

        return true;
    }

    public function renderQueue()
    {
        if  ( ! $this->queueExists) {
            return "An error occured when saving to the backend";
        }

        $queue = $this->queue;
        $queueItems = array();
        foreach ($queue["queue"] as $actionName => $queueAction) {
            $queueAction["data"] = rawurlencode(json_encode($queueAction));
            $queueItems[$actionName] = $queueAction;
        }
        $queue["queue"] = $queueItems;

        return $this->twig->render(
            'RedKiteCms/Resources/views/Queue/queue.html.twig',
            array("queue" => $this->queue, "queue_file" => $this->queueFile)
        );
    }

    private function checkForExistingQueue()
    {
        if (!file_exists($this->queueFile)) {
            return;
        }

        $this->queue = json_decode(file_get_contents($this->queueFile), true);
        $this->queueExists = true;
    }

    private function saveQueueFile($message)
    {
        $queueDir = dirname($this->queueFile);
        if (!is_dir($queueDir)) {
            mkdir($queueDir);
        }

        $queue = array(
            "error" => $message,
            "queue" => $this->queue["queue"],
        );
        FilesystemTools::writeFile($this->queueFile, json_encode($queue));
    }

    private function removeQueueFile()
    {
        if ($this->queueExists) {
            unlink($this->queueFile);
        }
    }
}