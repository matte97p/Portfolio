<?php

namespace App\Utils\Logger;

use DateTimeZone;
use Monolog\Handler\StreamHandler;

/**
 * Class Logger
 * @author Perino Matteo
 *
 * Logger methods
 */
class Logger extends \Monolog\Logger
{
    const ACTION_REQUEST = 'request';
    const ACTION_RESPONSE = 'response';

    /**
     * @var string
     */
    protected static $log_root;

    /**
     * @var bool
     */
    protected $logging = true;

    /**
     * Building logs filesystem, init logger
     *
     * @param string $name
     * @param string $path
     * @param array $handlers
     * @param array $processors
     * @param DateTimeZone|null $timezone
     */
    function __construct(string $name, string $path, array $handlers = [], array $processors = [], ?DateTimeZone $timezone = null)
    {
        parent::__construct($name, $handlers, $processors, $timezone);

        if($this->isLogging()) {
            $log_path = $this->getLogRoot() . $path;
            $log_name = $this->getName() . '.log';

            if (!is_dir($log_path)) {
                mkdir($log_path, 0755, true);
            }

            $log_file = fopen($log_path . $log_name, 'a+');

            if (!$log_file) {
                throw new \Error("Unable to open `{$this->getName()}` log file");
            }

            $log_stream = new StreamHandler($log_file);

            $formatter = new \Monolog\Formatter\LineFormatter(
                null, // Format of message in log, default [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n
                null, // Datetime format
                true, // allowInlineLineBreaks option, default false
                true  // ignoreEmptyContextAndExtra option, default false
            );

            $log_stream->setFormatter($formatter);
        } else {
            $log_stream = new \Monolog\Handler\NullHandler();
        }

        $this->pushHandler($log_stream);
    }

    public function getProcessMark()
    {
        return bin2hex(random_bytes(5));
    }

    /**
     * @return bool
     */
    public function isLogging(): bool
    {
        return $this->logging;
    }

    /**
     * @return string
     */
    protected function getLogRoot()
    {
        if( is_null(static::$log_root) ){
            static::$log_root = base_path() . '/storage/logs/';
        }

        return static::$log_root;
    }
}
