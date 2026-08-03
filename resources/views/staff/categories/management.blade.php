@extends('layouts.admin')
@section('content')
    <div id="StaffCategories">
        <h1>Edit Categories</h1>
        <div class="container">
            <div class="row">
    {{-- GEARS --}}
                <div class="gears table col-sm-6 col-md-3">
                    <h5>Bike Gears</h5>

                    <form method="GET" action="{{ route('category.search') }}">
                        <input type="text" name="gears" placeholder="Search gear amount">
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    @foreach ($speeds as $speed)
                    <div class="cat-value">
                        <p>{{ $speed->gears }}</p>
                        <a href="#" onclick="showme('edit-gears-{{ $speed->id }}'); return false;" class="edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                    </div>

                    <form method="POST" action="{{ route('category.edit', ["gears"]) }}" id="edit-gears-{{ $speed->id }}" class="editform">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="gearID" value="{{ $speed->id }}" />

                        <input type="number" id="gear" name="gear" value="{{ $speed->gears }}">

                        <div class="buttons">
                            <button class="confirm" type="submit">Confirm</button>
                            <button class="cancel" type="button" onclick="document.getElementById('edit-gears-{{ $speed->id }}').style.display='none'">
                                Cancel
                            </button>
                        </div>
                    </form>
                    @endforeach

                    <form method="POST" action="{{ route('category.create', ["gears"]) }}" id="newcat-gears">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="number" id="gearamount" name="gearamount" placeholder="gear amount">
                        <div class="buttons">
                            <button class="confirm" type="submit">Confirm</button>
                            <button class="cancel" type="button" onclick="document.getElementById('newcat-gears').style.display='none'">
                                Cancel
                            </button>
                        </div>
                    </form>

                    <button class="new-category">
                        <a href="#" onclick="showme('newcat-gears'); return false;">+ Add New</a>
                    </button>

                </div>

    {{-- PROVISIONS --}}
                <div class="provisions table col-sm-6 col-md-3">
                    <h5>Bike Provisions</h5>

                    <form method="GET" action="{{ route('category.search') }}">
                        <input type="text" name="provisions" placeholder="Search provision type">
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    @foreach ($provisions as $provision)
                    <div class="cat-value">
                        <p>{{ $provision->name }}</p>
                    </div>
                    @endforeach

                    <form method="POST" action="{{ route('category.create', ["provision"]) }}" id="newcat-provision">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="text" id="provname" name="provname" placeholder="Provision Type">
                        <div class="buttons">
                            <button class="confirm" type="submit">Confirm</button>
                            <button class="cancel" type="button" onclick="document.getElementById('newcat-provision').style.display='none'">
                                Cancel
                            </button>
                        </div>
                    </form>

                    <button class="new-category">
                        <a href="#" onclick="showme('newcat-provision'); return false;">+ Add New</a>
                    </button>

                </div>

    {{-- LOCATIONS --}}
                <div class="locations table col-sm-6 col-md-3">{{--  TODO: Add logntidude and latidude for imidiate map checkpoint addition --}}
                    <h5>Locations</h5>

                    <form method="GET" action="{{ route('category.search') }}">
                        <input type="text" name="locations" placeholder="Search location name">
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    @foreach ($locations as $location)
                    <div class="cat-value">
                        <p>{{ $location->name }}</p>
                        <a href="#" onclick="showme('edit-location-{{ $location->id }}'); return false;" class="edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <a href="#" onclick="showme('delconfirm-location-{{ $location->id }}'); return false;" class="delete">
                            <i class="fa-regular fa-trash-can"></i>
                        </a>
                    </div>

                    <form method="POST" action="{{ route('category.edit', ["location"]) }}" id="edit-location-{{ $location->id }}" class="editform">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="locID" value="{{ $location->id }}" />

                        <input type="text" id="loc" name="loc" value="{{ $location->name }}">
                        <input type="number" step="0.0000001" id="lat" name="lat" value="{{ $location->latitude }}">
                        <input type="number" step="0.0000001" id="long" name="long" value="{{ $location->longitude }}">

                        <div class="buttons">
                            <button class="confirm" type="submit">Confirm</button>
                            <button class="cancel" type="button" onclick="document.getElementById('edit-location-{{ $location->id }}').style.display='none'">
                                Cancel
                            </button>
                        </div>
                    </form>

                    <div class="delconfirm" id="delconfirm-location-{{ $location->id }}">
                        <h3>Warning!</h3>
                        <p>You are about to delete "{{ $location->name }}" from this category</p>
                        <div class="buttons">
                            <a href="{{ route('category.delete',[$location->id, "location"]) }}" class="delete">Delete</a>
                            <button class="cancel" type="button" onclick="document.getElementById('delconfirm-location-{{ $location->id }}').style.display='none'">
                                Cancel
                            </button>
                        </div>
                    </div>
                    @endforeach

                    <form method="POST" action="{{ route('category.create', ["location"]) }}" id="newcat-location">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="text" id="locname" name="locname" placeholder="Location Name"><br>
                        <input type="number" step="0.0000001" id="latitude" name="latitude" placeholder="Latitude"><br>
                        <input type="number" step="0.0000001" id="longitude" name="longitude" placeholder="Longitude">
                        <div class="buttons">
                            <button class="confirm" type="submit">Confirm</button>
                            <button class="cancel" type="button" onclick="document.getElementById('newcat-location').style.display='none'">
                                Cancel
                            </button>
                        </div>
                    </form>

                    <button class="new-category">
                        <a href="#" onclick="showme('newcat-location'); return false;">+ Add New</a>
                    </button>
                </div>

    {{-- STATUS --}}
                <div class="statuses table col-sm-6 col-md-3">
                    <h5>Order Statuses</h5>

                    <form method="GET" action="{{ route('category.search') }}">
                        <input type="text" name="statuses" placeholder="Search status phrase">
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>


                    @foreach ($statuses as $status)
                    <div class="cat-value">
                        <p>{{ $status->step }}. {{ $status->name }}</p>
                        <a href="#" onclick="showme('edit-status-{{ $status->id }}'); return false;" class="edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                        <a href="#" onclick="showme('delconfirm-status-{{ $status->id }}'); return false;" class="delete">
                            <i class="fa-regular fa-trash-can"></i>
                        </a>
                    </div>

                    <form method="POST" action="{{ route('category.edit', ["status"]) }}" id="edit-status-{{ $status->id }}" class="editform">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="statID" value="{{ $status->id }}" />

                        <input type="text" id="stat" name="stat" value="{{ $status->name }}">
                        <input type="number" id="step" name="step" value="{{ $status->step }}">

                        <div class="buttons">
                            <button class="confirm" type="submit">Confirm</button>
                            <button class="cancel" type="button" onclick="document.getElementById('edit-status-{{ $status->id }}').style.display='none'">
                                Cancel
                            </button>
                        </div>
                    </form>

                    <div class="delconfirm" id="delconfirm-status-{{ $status->id }}">
                        <h3>Warning!</h3>
                        <p>You are about to delete "{{ $status->name }}" from this category</p>
                        <div class="buttons">
                            <a href="{{ route('category.delete',[$status->id, "status"]) }}" class="delete">Delete</a>
                            <button class="cancel" type="button" onclick="document.getElementById('delconfirm-status-{{ $status->id }}').style.display='none'">
                                Cancel
                            </button>
                        </div>
                    </div>
                    @endforeach

                    <form method="POST" action="{{ route('category.create', ["status"]) }}" id="newcat-status">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="number" id="statstep" name="statstep" placeholder="Status Step"><br>
                        <input type="text" id="statname" name="statname" placeholder="Status Phrase">
                        <div class="buttons">
                            <button class="confirm" type="submit">Confirm</button>
                            <button class="cancel" type="button" onclick="document.getElementById('newcat-status').style.display='none'">
                                Cancel
                            </button>
                        </div>
                    </form>

                    <button class="new-category">
                        <a href="#" onclick="showme('newcat-status'); return false;">+ Add New</a>
                    </button>

                </div>

    {{-- TYPES --}}
                <div class="types table col-sm-6 col-md-3">
                    <h5>Bike Types</h5>

                    <form method="GET" action="{{ route('category.search') }}">
                        <input type="text" name="types" placeholder="Search bike type">
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    @foreach ($types as $type)
                    <div class="cat-value">
                        <p>{{ $type->name }}</p>
                        <a href="#" onclick="showme('edit-type-{{ $type->id }}'); return false;" class="edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                    </div>

                    <form method="POST" action="{{ route('category.edit', ["type"]) }}" id="edit-type-{{ $type->id }}" class="editform">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="typeID" value="{{ $type->id }}" />

                        <input type="text" id="type" name="type" value="{{ $type->name }}">

                        <div class="buttons">
                            <button class="confirm" type="submit">Confirm</button>
                            <button class="cancel" type="button" onclick="document.getElementById('edit-type-{{ $type->id }}').style.display='none'">
                                Cancel
                            </button>
                        </div>
                    </form>
                    @endforeach

                    <form method="POST" action="{{ route('category.create', ["type"]) }}" id="newcat-type">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="text" id="typename" name="typename" placeholder="Bike Type">
                        <div class="buttons">
                            <button class="confirm" type="submit">Confirm</button>
                            <button class="cancel" type="button" onclick="document.getElementById('newcat-type').style.display='none'">
                                Cancel
                            </button>
                        </div>
                    </form>

                    <button class="new-category">
                        <a href="#" onclick="showme('newcat-type'); return false;">+ Add New</a>
                    </button>
                </div>

    {{-- BRANDS --}}
                <div class="brands table col-sm-6 col-md-3">
                    <h5>Bike Brands</h5>

                    <form method="GET" action="{{ route('category.search') }}">
                        <input type="text" name="brands" placeholder="Search bike brand">
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    @foreach ($brands as $brand)
                    <div class="cat-value">
                        <p>{{ $brand->name }}</p>
                        <a href="#" onclick="showme('edit-brand-{{ $brand->id }}'); return false;" class="edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                    </div>

                    <form method="POST" action="{{ route('category.edit', ["brand"]) }}" id="edit-brand-{{ $brand->id }}" class="editform">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="brandID" value="{{ $brand->id }}" />

                        <input type="text" id="brand" name="brand" value="{{ $brand->name }}">

                        <div class="buttons">
                            <button class="confirm" type="submit">Confirm</button>
                            <button class="cancel" type="button" onclick="document.getElementById('edit-brand-{{ $brand->id }}').style.display='none'">
                                Cancel
                            </button>
                        </div>
                    </form>
                    @endforeach

                    <form method="POST" action="{{ route('category.create', ["brand"]) }}" id="newcat-brand">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="text" id="brandname" name="brandname" placeholder="Brand Name">
                        <div class="buttons">
                            <button class="confirm" type="submit">Confirm</button>
                            <button class="cancel" type="button" onclick="document.getElementById('newcat-brand').style.display='none'">
                                Cancel
                            </button>
                        </div>
                    </form>

                    <button class="new-category">
                        <a href="#" onclick="showme('newcat-brand'); return false;">+ Add New</a>
                    </button>

                </div>
            </div>
        </div>
    </div>
    <script>
        function showme(id)
        {
            document.getElementById(id).style.display = 'block';
        }
    </script>
@endsection
