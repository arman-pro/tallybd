<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set database backup schedue By Arman Ali';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file_name ="nirapadb_Masumtraders-". date('d-M-y') . '-'. rand() . '.zip';
        \Artisan::call('backup:run --only-db --filename='.$file_name);
        $filePath = storage_path() . "/app/Laravel/".$file_name;
        if(file_exists($filePath)){
            $fileData = \File::get($filePath);
            if(\Storage::cloud('google')->put($file_name, $fileData)){
                unlink($filePath);
                return true;
            }
        }
        return 0;
    }
}
