<?php

namespace App\View\Composers;

use App\Models\DaftarGiling;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotificationComposer
{
    public function compose(View $view)
    {
        try {
            Log::info('NotificationComposer is running');

            // Fetch data with minimal needed relations
            $latestNotifications = DaftarGiling::with(['giling.petani:id,nama'])
                ->select('id', 'giling_id', 'created_at')
                ->whereHas('giling.petani') // Ensure related data exists
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            Log::info('Fetched notifications count: ' . $latestNotifications->count());

            $mappedNotifications = $latestNotifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'petani_nama' => $notification->giling->petani->nama ?? 'Unknown',
                    'created_at' => Carbon::parse($notification->created_at),
                    // Add any other needed fields here
                ];
            });

            Log::debug('Mapped notifications:', $mappedNotifications->toArray());
            $view->with('latestNotifications', $mappedNotifications);

            Log::info('Successfully set notifications to view');
        } catch (\Exception $e) {
            Log::error('Error in NotificationComposer: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            $view->with('latestNotifications', collect([]));
        }
    }
}
