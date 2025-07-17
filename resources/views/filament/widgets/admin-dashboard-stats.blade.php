<x-filament::widget>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <x-filament::stats.card
            label="Tools Currently Checked Out"
            :value="$this->getStats()['toolsCheckedOut']"
        />
        <x-filament::stats.card
            label="Total Leased Units Revenue"
            :value="'Ksh ' . number_format($this->getStats()['leasedUnitsRevenue'])"
        />
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h3 class="text-lg font-bold mb-4">Upcoming Scheduled Visits</h3>
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Location</th>
                    <th class="px-4 py-2 text-left">Responsible</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->getUpcomingVisits() as $visit)
                    <tr>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($visit->scheduled_at)->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2">{{ $visit->location }}</td>
                        <td class="px-4 py-2">{{ $visit->responsible_person }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-center text-gray-500">No upcoming visits.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament::widget> 