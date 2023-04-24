<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;
use Illuminate\Filesystem\Filesystem;
use App\Traits\BasicMigrationCommand;

class MakeMigrationInherit extends Command
{
    use BasicMigrationCommand;

    /**
     * The table and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:inherit {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create inherit table Migration';

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
        return __DIR__ . '/../../../stubs/migration.inherits.stub';
    }

    /**
     * Get the full path of generate class
     *
     * @return string
     */
    protected function getSourceFilePath()
    {
        return __DIR__ . '/../../../database/migrations/' . $this->getDatePrefix() . '_create_' . strtolower($this->argument('table')) . '_inherits_table.php';
    }
}
