<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Live Election Prediction') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-2xl font-bold mb-4">Election Outcome Projection</h3>
                    <p class="mb-4 text-gray-600">
                        This prediction algorithm calculates the current velocity of votes (votes per minute) and projects the final outcome based on the remaining time in the election.
                    </p>

                    @if($predictionData['status'] === 'no_data')
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                            <p class="font-bold">Waiting for Votes</p>
                            <p>{{ $predictionData['message'] }}</p>
                        </div>
                    @else
                        @if($predictionData['status'] === 'early_prediction')
                            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                                <p class="font-bold">Early Prediction Notice</p>
                                <p>The election has just started. Projections may be volatile until more votes are cast.</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-gray-50 p-4 rounded-lg shadow">
                                <h4 class="text-lg font-semibold text-gray-700 mb-2">Election Metrics</h4>
                                <ul class="list-disc pl-5">
                                    <li><strong>Time Elapsed:</strong> {{ $predictionData['time_elapsed_minutes'] ?? 0 }} minutes</li>
                                    <li><strong>Time Remaining:</strong> {{ $predictionData['time_remaining_minutes'] ?? 0 }} minutes</li>
                                </ul>
                            </div>

                            @if($predictionData['winner'])
                            <div class="bg-green-50 p-4 rounded-lg shadow border border-green-200">
                                <h4 class="text-lg font-semibold text-green-800 mb-2">Predicted Winner</h4>
                                <div class="text-3xl font-bold text-green-600">
                                    {{ $predictionData['winner']['leader_name'] }}
                                </div>
                                <div class="text-gray-600">
                                    {{ $predictionData['winner']['party_name'] }}
                                </div>
                                <div class="mt-2 text-sm font-semibold text-green-700">
                                    Win Probability: {{ $predictionData['winner']['win_probability'] }}%
                                </div>
                            </div>
                            @endif
                        </div>

                        <h4 class="text-xl font-bold mb-4">Projection Details</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border">
                                <thead>
                                    <tr class="bg-gray-100 border-b">
                                        <th class="text-left py-3 px-4 font-semibold text-sm">Leader</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm">Party</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm">Current Votes</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm">Velocity (votes/min)</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm">Projected Total</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm">Win Probability</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($predictionData['predictions'] as $prediction)
                                    <tr class="border-b hover:bg-gray-50 {{ $loop->first ? 'bg-green-50' : '' }}">
                                        <td class="py-3 px-4 font-semibold">{{ $prediction['leader_name'] }}</td>
                                        <td class="py-3 px-4">{{ $prediction['party_name'] }}</td>
                                        <td class="py-3 px-4">{{ $prediction['current_votes'] }}</td>
                                        <td class="py-3 px-4 text-gray-500">{{ $prediction['velocity_per_minute'] }}</td>
                                        <td class="py-3 px-4 font-bold text-indigo-600">{{ $prediction['projected_total'] }}</td>
                                        <td class="py-3 px-4">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $prediction['win_probability'] }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $prediction['win_probability'] }}%</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
