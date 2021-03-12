<?php

namespace App\Console\Commands;

use App\Models\Log;
use Illuminate\Console\Command;
use Kassner\LogParser\LogParser;

class ParseAccessLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:accessloog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse access log.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $parser = new LogParser();

        $lines = file('/var/log/apache2/access.log', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $entry = $parser->parse($line);

            $log = new Log([
                'host' => $entry->host,
                'user' => $entry->user,
                'stamp' => $entry->stamp,
                'time' => $entry->time,
                'request' => $entry->request,
                'status' => $entry->status,
                'responseBytes' => $entry->responseBytes
            ]);

            $log->save();
        }
    }
}
