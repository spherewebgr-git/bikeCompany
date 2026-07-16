<x-app-layout>

    <div class="page-body">
        <div class="container">
            <h2>Αγορά Ποδηλάτων</h2>

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
