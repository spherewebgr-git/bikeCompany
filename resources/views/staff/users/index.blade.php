<x-app-layout>
    <div id="userManagement">
    <div class="container">
        <h1>User Management</h1>

        <form
            method="GET"
            action="{{ route('staff.users.index') }}"
            class="user-filters"
        >
            <div class="filter-field">
                <label for="role">Role:</label>

                <select name="role" id="role">
                    <option value="" @selected(!$selectedRole)>
                        All users
                    </option>

                    <option
                        value="customer"
                        @selected($selectedRole === 'customer')
                    >
                        Customers
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
                Filter
            </button>

            @if($selectedRole)
                <a
                    href="{{ route('staff.users.index') }}"
                    class="btn-clear"
                >
                    Clear
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
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit">
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
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button type="submit">
                                        Remove Staff
                                    </button>
                                </form>
                            @endif

{{--                            @if(auth()->id() !== $user->id)--}}
{{--                                <form--}}
{{--                                    method="POST"--}}
{{--                                    action="{{ route('staff.users.delete', $user) }}"--}}
{{--                                    onsubmit="return confirm('Delete this user?')"--}}
{{--                                >--}}
{{--                                    @csrf--}}
{{--                                    @method('DELETE')--}}

{{--                                    <button type="submit">--}}
{{--                                        Delete--}}
{{--                                    </button>--}}
{{--                                </form>--}}
{{--                            @endif--}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</x-app-layout>
