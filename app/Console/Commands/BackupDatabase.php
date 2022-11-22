<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Backup the database';

    protected $process;
    protected $process_gzip;

    public function __construct()
    {
        parent::__construct();
        $path = storage_path('backup/'.date('Y-m-d_H:i:s').'-backup.sql');
        $this->process = new Process(sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            "'".config('database.connections.mysql.password')."'",
            config('database.connections.mysql.database'),
            $path
        ));
        $this->process_gzip = new Process(sprintf('gzip -9 '.$path));
    }

    public function handle()
    {
        try {
            $this->process->mustRun();
            $this->process_gzip->mustRun();

            $files = \Storage::files('backup');
            if ((count($files)) > 11) {
                \Storage::delete($files[1]);
            }

        } catch (ProcessFailedException $exception) {
            dd($exception->getMessage());

        }
    }
}
