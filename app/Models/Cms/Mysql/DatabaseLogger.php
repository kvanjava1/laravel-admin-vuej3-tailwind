<?php

namespace App\Models\Cms\Mysql;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Illuminate\Support\Facades\DB;

class DatabaseLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('database');
        $logger->pushHandler(new class($config) extends AbstractProcessingHandler {
            protected $config;

            public function __construct(array $config)
            {
                $this->config = $config;
                parent::__construct(
                    $config['level'] ?? Logger::DEBUG,
                    true // Bubble up to allow stacking with other handlers
                );
            }

            protected function write(\Monolog\LogRecord $record): void
            {
                DB::table($this->config['table'] ?? 'logs')->insert([
                    'channel' => $record->channel,
                    'level' => $record->level->getName(),
                    'message' => $record->message,
                    'context' => json_encode($record->context),
                    'created_at' => now(),
                ]);
            }
        });

        return $logger;
    }

}