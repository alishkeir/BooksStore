<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class ActivityLogCleanUpCommand extends Command
{
    protected $signature = 'activity-log:cleanup';

    private $days = 21;

    protected $description;

    public function __construct()
    {
        parent::__construct();
        $this->description = 'Cleanup activity log rows older than '.$this->days.' days';
    }

    public function handle()
    {
        $logs = DB::table('activity_log')->where('created_at', '<', now()->subDays($this->days))->delete();
        $log = new Activity();
        $log->log_name = 'system';
        $log->description = 'Cleanup activity log rows older than '.$this->days.' days';
        $log->subject_id = null;
        $log->subject_type = 'ActivityLog';
        $log->causer_id = 0;
        $log->causer_type = 'Cron';
        $log->properties = json_encode(['logs' => $logs], JSON_UNESCAPED_SLASHES);
        $log->created_at = Carbon::now();
        $log->updated_at = Carbon::now();
        $log->save();
    }
}
