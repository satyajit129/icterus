<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FacebookLeadService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CollectFacebookLeadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facebook:collect-leads
                            {form_id : The Facebook form ID to collect leads from}
                            {--start-date= : Start date for lead collection (Y-m-d format)}
                            {--end-date= : End date for lead collection (Y-m-d format)}
                            {--optimized : Use optimized collection method}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect Facebook leads from a specific form using optimized methods';

    /**
     * The Facebook Lead Service instance.
     *
     * @var FacebookLeadService
     */
    protected $facebookLeadService;

    /**
     * Create a new command instance.
     *
     * @param FacebookLeadService $facebookLeadService
     */
    public function __construct(FacebookLeadService $facebookLeadService)
    {
        parent::__construct();
        $this->facebookLeadService = $facebookLeadService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $formId = $this->argument('form_id');
        $startDate = $this->option('start-date');
        $endDate = $this->option('end-date');
        $optimized = $this->option('optimized');

        $this->info("Starting Facebook leads collection for form: {$formId}");

        if ($startDate) {
            $this->info("Start date: {$startDate}");
        }
        if ($endDate) {
            $this->info("End date: {$endDate}");
        }

        if ($optimized) {
            $this->info("Using optimized collection method...");
        }

        // Create a mock request object
        $request = new Request();
        $request->merge([
            'form_id' => $formId,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        try {
            if ($optimized) {
                $result = $this->facebookLeadService->handleCollectLeadsOptimized($request);
            } else {
                $result = $this->facebookLeadService->handleCollectLeads($request);
            }

            $this->info("Lead collection completed successfully!");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Error collecting leads: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
