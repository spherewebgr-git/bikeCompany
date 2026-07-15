<x-app-layout>

    <div class="container">

        <h1>Ποδήλατα</h1>

{{--        <a href="{{ route('dashboard.bikes.create') }}"--}}
{{--           class="btn btn-primary">--}}
{{--            + Νέο Ποδήλατο--}}
{{--        </a>--}}

        <div class="bike-list">

            @foreach($bikes as $bike)

                @include('bikes.card')

            @endforeach

        </div>

    </div>

</x-app-layout>
