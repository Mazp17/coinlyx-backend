<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController extends Controller
{
    public function downloadLog(Request $request)
    {
        $filename = 'activity_log.csv';
        $logs = Activity::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Description', 'Subject Type', 'Subject ID', 'Causer Type', 'Causer ID', 'Properties', 'Created At', 'Updated At']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->description,
                    $log->subject_type,
                    $log->subject_id,
                    $log->causer_type,
                    $log->causer_id,
                    json_encode($log->properties),
                    $log->created_at,
                    $log->updated_at
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
