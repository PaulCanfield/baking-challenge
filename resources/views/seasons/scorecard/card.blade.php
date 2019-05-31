<div class="card mb-2">
    <table>
        <tr>
            <th>Name</th>
            <th>Predictions</th>
            <th>Correct</th>
            <th>Bonus</th>
            <th>Points Awarded</th>
            <th>Running Total</th>
        </tr>
        @php
            $runningTotal = 0;
        @endphp
        @foreach($season->episodes as $index => $episode)
            @if($episode->episode == 1)
                @continue
            @endif

            @php
                $points = $episode->userPoints();
                $runningTotal += $points;
            @endphp
            <tr>
                <td>{{ $episode->episodeName() }}</td>
                <td>{{ $episode->userPredictions()->count() }}</td>
                <td>{{ $episode->correctPredictionsCount() }}</td>
                <td><span class="material-icons">{{ $episode->bonus() ? 'check_circle' : 'highlight_off' }}</span></td>
                <td>{{ $points }}</td>
                <td>{{ $runningTotal }}</td>
            </tr>
        @endforeach
    </table>
</div>