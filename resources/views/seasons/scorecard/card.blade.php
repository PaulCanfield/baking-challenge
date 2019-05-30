<div>
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
            @php
                $points = $episode->userPoints();
                $runningTotal += $points;
            @endphp
            <tr>
                <td>{{ $episode->title }}</td>
                <td>{{ $episode->userPredictions()->count() }}</td>
                <td>{{ $episode->correctPredictionsCount() }}</td>
                <td><span class="material-icons">{{ $episode->bonus() ? 'check_circle' : 'highlight_off' }}</span></td>
                <td>{{ $points }}</td>
                <td>{{ $runningTotal }}</td>
            </tr>
        @endforeach
    </table>
</div>