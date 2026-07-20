@extends('layouts.admin')
@section('content')
    <div id="StaffCategories">
        <div class="container">

            <div class="gears table">
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
                    <a href="{{ route('category.delete',[$speed->id, "gears"]) }}" class="delete">
                        <i class="fa-regular fa-trash-can"></i>
                    </a>
                </div>
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
                    <a href="#" onclick="addSpeed(); return false;">+ Add New</a>
                </button>           
            </div>

            <div class="provisions table">
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
                    <a href="{{ route('category.delete',[$provision->id, "provision"]) }}" class="delete">
                        <i class="fa-regular fa-trash-can"></i>
                    </a>
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
                    <a href="#" onclick="addProvision(); return false;">+ Add New</a>
                </button>  
            </div>

            <div class="locations table">
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
                    <a href="{{ route('category.delete',[$location->id, "location"]) }}" class="delete">
                        <i class="fa-regular fa-trash-can"></i>
                    </a>
                </div>
                @endforeach
                <form method="POST" action="{{ route('category.create', ["location"]) }}" id="newcat-location">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="text" id="locname" name="locname" placeholder="Location Name">
                    <div class="buttons">
                        <button class="confirm" type="submit">Confirm</button>
                        <button class="cancel" type="button" onclick="document.getElementById('newcat-location').style.display='none'">
                            Cancel
                        </button>
                    </div>
                </form> 
                <button class="new-category">
                    <a href="#" onclick="addLocation(); return false;">+ Add New</a>
                </button> 
            </div>

            <div class="statuses table">
                <h5>Order Statuses</h5>
                <form method="GET" action="{{ route('category.search') }}">
                    <input type="text" name="statuses" placeholder="Search status phrase">
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
                @foreach ($statuses as $status)
                <div class="cat-value">
                    <p>{{ $status->name }}</p>
                    <a href="{{ route('category.delete',[$status->id, "status"]) }}" class="delete">
                        <i class="fa-regular fa-trash-can"></i>
                    </a>
                </div>
                @endforeach
                <form method="POST" action="{{ route('category.create', ["status"]) }}" id="newcat-status">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="text" id="statname" name="statname" placeholder="Status Phrase">
                    <div class="buttons">
                        <button class="confirm" type="submit">Confirm</button>
                        <button class="cancel" type="button" onclick="document.getElementById('newcat-status').style.display='none'">
                            Cancel
                        </button>
                    </div>
                </form> 
                <button class="new-category">
                    <a href="#" onclick="addStatus(); return false;">+ Add New</a>
                </button> 
            </div>

            <div class="types table">
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
                    <a href="{{ route('category.delete',[$type->id, "type"]) }}" class="delete">
                        <i class="fa-regular fa-trash-can"></i>
                    </a>
                </div>
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
                    <a href="#" onclick="addType(); return false;">+ Add New</a>
                </button> 
            </div>

            <div class="brands table">
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
                    <a href="{{ route('category.delete',[$brand->id, "brand"]) }}" class="delete">
                        <i class="fa-regular fa-trash-can"></i>
                    </a>
                </div>
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
                    <a href="#" onclick="addBrand(); return false;">+ Add New</a>
                </button> 
            </div>

        </div>
    </div>
    <script>
        function addSpeed(id)
        {
            document.getElementById('newcat-gears').style.display = 'block';
        }

        function addProvision(id)
        {
            document.getElementById('newcat-provision').style.display = 'block';
        }

        function addLocation(id)
        {
            document.getElementById('newcat-location').style.display = 'block';
        }

        function addStatus(id)
        {
            document.getElementById('newcat-status').style.display = 'block';
        }

        function addType(id)
        {
            document.getElementById('newcat-type').style.display = 'block';
        }

        function addBrand(id)
        {
            document.getElementById('newcat-brand').style.display = 'block';
        }
    </script>
@endsection