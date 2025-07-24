<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportExternalData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $type;
    protected string $dateFrom;
    protected string $dateTo;
    protected string $uniqueKey;

    /**
     * Create a new job instance.
     */
    public function __construct(string $type, string $dateFrom, string $dateTo, string $uniqueKey)
    {
        $this->type = $type;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->uniqueKey = $uniqueKey;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $page = 1;
        $limit = 500;
        $totalImported = 0;

        do {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.custom_api.key'),
            ])->get(config('services.custom_api.base_url') . '/' . $this->type, [
                'page'     => $page,
                'limit'    => $limit,
                'dateFrom' => $this->dateFrom,
                'dateTo'   => $this->dateTo,
                'key'      => config('services.custom_api.key'),
            ]);

            if ($response->failed()) {
               break;
            }

            $data = $response->json('data');

            if (empty($data)) {
                break;
            }

            try {
                DB::table($this->type)->upsert(
                    $data,
                    [$this->uniqueKey]
                );
                $totalImported += count($data);
            } catch (\Throwable $e) {
                break;
            }

            $page++;
        } while (count($data) === $limit);Log::info("✅ Import completed for {$this->type}. Total imported: {$totalImported}");
    }
}
