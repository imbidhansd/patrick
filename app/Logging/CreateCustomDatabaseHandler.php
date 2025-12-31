<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;
use Illuminate\Support\Facades\DB;

class CreateCustomDatabaseHandler extends AbstractProcessingHandler
{
    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        // Retrieve the key_identifier and key_identifier_type values from the log context (if provided)
        $keyIdentifier = $record['context']['key_identifier'] ?? null;
        $keyIdentifierType = $record['context']['key_identifier_type'] ?? null;

        // Save the log record to the database
        DB::table('custom_logs')->insert([
            'channel' => $record['channel'],
            'level' => $record['level'],
            'message' => $record['message'],
            'context' => json_encode($record['context']),
            'key_identifier' => $keyIdentifier,
            'key_identifier_type' => $keyIdentifierType,
            'created_at' => now(),
        ]);
    }
}
