<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class InstallLaravelExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Laravel Excel package';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Installing Laravel Excel package...');
        
        // Run composer require command
        $process = new Process(['composer', 'require', 'maatwebsite/excel:^3.1']);
        $process->setTimeout(300); // 5 minutes should be enough
        
        // Output real-time feedback
        $this->info('Running: ' . $process->getCommandLine());
        
        $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });
        
        if (!$process->isSuccessful()) {
            $this->error('Failed to install Laravel Excel package!');
            $this->error($process->getErrorOutput());
            return Command::FAILURE;
        }
        
        $this->info('Laravel Excel package installed successfully!');
        
        // Publish the configuration
        $this->call('vendor:publish', [
            '--provider' => 'Maatwebsite\Excel\ExcelServiceProvider',
            '--tag' => 'config'
        ]);
        
        $this->info('You can now use Laravel Excel for imports and exports.');
        
        return Command::SUCCESS;
    }
}
