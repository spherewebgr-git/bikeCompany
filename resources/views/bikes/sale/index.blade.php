<x-app-layout>

    <div class="page-header">
        <div class="page-header-container">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">{{ __('Home') }}</a> /
                <a href="{{ route('bikes.sale') }}">{{ __('Buy Bikes') }}</a>
            </nav>
        </div>
    </div>

    <div class="page-body">
        <div class="container">



            <h2 class="section-heading">Αγορά Ποδηλάτων</h2>

            <div class="blogs-list">

                <div class="row">
                    @foreach($bikes as $bike)
                        @include('bikes.partials.card')
                    @endforeach

                </div>
                <div class="pagination-wrapper">
                    {{ $bikes->links() }}
                </div>

            </div>

        </div>
    </div>

</x-app-layout>
