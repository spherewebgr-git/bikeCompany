<div class="order-status">
    @foreach ($status as $stat)
        @if ($stat->step > 0)
            @if ($order->status->step > $stat->step)
                <div class="stepinfo">
                    <p class="past statpoint">{{ $stat->step }}</p>
                    <p class="past statdesc">{{ $stat->name }}</p>
                </div>
            @elseif ($order->status->step == $stat->step)
                <div class="stepinfo">
                    <p class="current statpoint">{{ $stat->step }}</p>
                    <p class="current statdesc">{{ $stat->name }}</p>
                </div>
            @else
                <div class="stepinfo">
                    <p class="statpoint">{{ $stat->step }}</p>
                    <p class="statdesc">{{ $stat->name }}</p>
                </div>
            @endif
        @endif
    @endforeach
</div>