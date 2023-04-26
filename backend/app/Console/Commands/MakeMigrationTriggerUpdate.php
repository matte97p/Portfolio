<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;
use Illuminate\Filesystem\Filesystem;
use App\Traits\GenericMigrationCommand;

class MakeMigrationTriggerUpdate extends Command
{
    use GenericMigrationCommand;

    /**
     * The table and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trigger_u {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Trigger Update Migration';

    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Return the stub file path
     * @return string
     *
     */
    protected function getStubPath()
    {
        return __DIR__ . '/../../../stubs/migration.trigger_update.stub';
    }

    /**
     * Get the full path of generate class
     *
     * @return string
     */
    protected function getSourceFilePath()
    {
        return __DIR__ . '/../../../database/migrations/' . $this->getDatePrefix() . '_create_' . $this->tableName() . '_update_trigger.php';
    }
}
