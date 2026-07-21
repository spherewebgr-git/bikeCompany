@extends('layouts.admin')
@section('content')
    <div id="userManagement">
    <div class="container">
        <h1>User Management</h1>

        <form
            method="GET"
            action="{{ route('staff.users.index') }}"
            class="user-filters"
        >
            <div class="search-group">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search users"
                >

                <button type="submit" class="btn-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>

            <div class="filter-field">
                <label for="role">Role:</label>

                <select name="role" id="role">
                    <option value="" @selected(!$selectedRole)>
                        Any
                    </option>

                    <option
                        value="customer"
                        @selected($selectedRole === 'customer')
                    >
                        Customer
                    </option>

                    <option
                        value="staff"
                        @selected($selectedRole === 'staff')
                    >
                        Staff
                    </option>
                </select>
            </div>

            <button type="submit" class="btn-filter">
                FILTER
            </button>

            @if($selectedRole || request('search'))
                <a
                    href="{{ route('staff.users.index') }}"
                    class="btn-clear"
                >
                    CLEAR
                </a>
            @endif
        </form>

        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            {{ $user->first_name }}
                            {{ $user->last_name }}
                        </td>

                        <td>{{ $user->email }}</td>

                        <td>{{ $user->phone }}</td>

                        <td>{{ $user->role?->name }}</td>

                        <td>
                            @if($user->role?->name === 'customer')
                                <form
                                    method="POST"
                                    action="{{ route('staff.users.promote', $user) }}"
                                    class="promote-form"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="btn-promote">
                                        Make Staff
                                    </button>
                                </form>
                            @endif

                            @if(
                                $user->role?->name === 'staff'
                                && auth()->id() !== $user->id
                            )
                                <form
                                    method="POST"
                                    action="{{ route('staff.users.demote', $user) }}"
                                    class="demote-form"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="btn-demote">
                                        Remove Staff
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>

    <script>
        document.querySelectorAll('.promote-form').forEach(form => {

            form.addEventListener('submit', function (e) {

                e.preventDefault();

                swal({
                    title: "Are you sure?",
                    text: "This user will become a staff member.",
                    icon: "warning",
                    buttons: {
                        cancel: "Cancel",
                        confirm: {
                            text: "Yes, Promote",
                            value: true
                        }
                    }
                }).then((willPromote) => {

                    if (willPromote) {
                        form.submit();
                    }

                });

            });

        });
    </script>

    <script>
        document.querySelectorAll('.demote-form').forEach(form => {

            form.addEventListener('submit', function (e) {

                e.preventDefault();

                swal({
                    title: "Are you sure?",
                    text: "This user will be demoted.",
                    icon: "warning",
                    buttons: {
                        cancel: "Cancel",
                        confirm: {
                            text: "Yes, Remove Staff",
                            value: true
                        }
                    }
                }).then((willPromote) => {

                    if (willPromote) {
                        form.submit();
                    }

                });

            });

        });
    </script>
@endsection
