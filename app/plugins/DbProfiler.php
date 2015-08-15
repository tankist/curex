<?php

namespace Plugin;

use Phalcon\Db\Profiler;
use Phalcon\Events\Event;
use Phalcon\Logger\AdapterInterface as LoggerAdapterInterface;
use Phalcon\Db\AdapterInterface as DbAdapterInterface;
use Phalcon\Logger;
use Phalcon\Mvc\User\Plugin;

class DbProfiler extends Plugin
{

    /**
     * @var Profiler
     */
    protected $profiler;

    /**
     * @var LoggerAdapterInterface
     */
    protected $logger;

    /**
     * @var int
     */
    protected $priority = Logger::DEBUG;

    /**
     * @var float
     */
    private $previousTotalExecutionTime = 0;

    /**
     * @param LoggerAdapterInterface $_logger
     * @param Profiler $_profiler
     * @param $priority
     */
    public function __construct(LoggerAdapterInterface $_logger, Profiler $_profiler, $priority = Logger::DEBUG)
    {
        $this->logger = $_logger;
        $this->profiler = $_profiler;
        $this->priority = $priority;
    }

    /**
     * @param Event $event
     * @param DbAdapterInterface $connection
     */
    public function beforeQuery(Event $event, DbAdapterInterface $connection)
    {
        $this->profiler->startProfile($connection->getSQLStatement());
    }

    /**
     * @param Event $event
     * @param DbAdapterInterface $connection
     */
    public function afterQuery(Event $event, DbAdapterInterface $connection)
    {
        $this->profiler->stopProfile();
        $sqlVariables = $connection->getSQLVariables() ?: [];

        foreach ($sqlVariables as $key => $value) {
            if ($key[0] !== ':') {
                unset($sqlVariables[$key]);
                $key = ':' . $key;
            }
            $sqlVariables[$key] = !is_array($value)
                ? $connection->escapeString($value)
                : array_map(function ($v) use ($connection) {
                    return $connection->escapeString($v);
                }, $value);
        }

        $statement = strtr($connection->getRealSQLStatement(), $sqlVariables);
        $time = $this->profiler->getTotalElapsedSeconds() - $this->previousTotalExecutionTime;
        $this->previousTotalExecutionTime = $this->profiler->getTotalElapsedSeconds();

        $this->logger->log(
            sprintf('%s [Execution time: %.4f sec.]', $statement, round($time, 4)),
            $this->priority
        );
    }

    /**
     * @return Profiler
     */
    public function getProfiler()
    {
        return $this->profiler;
    }

    public function __destruct()
    {
        if ($this->logger && $this->profiler) {
            $this->logger->log(
                sprintf(
                    'Total SQL execution time (%d queries): %.4f sec.]',
                    $this->profiler->getNumberTotalStatements(),
                    round($this->profiler->getTotalElapsedSeconds(), 4)
                ),
                $this->priority
            );
        }
    }

}