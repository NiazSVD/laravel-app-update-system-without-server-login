@extends('backend.master')

@section('content')

<div class="page-content d-flex justify-content-center align-items-center text-dark mb-5">
    <section class="row g-4 w-100 d-flex justify-content-center">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Manage Permissions for Role: {{ Str::title($role->name) }}</h3>
            <div>
                <a class="btn btn-primary" href="{{ route('admin.roles')}}">Back</a>
            </div>
        </div>
        <div class="card-content">
            <div class="card-body">
                <form action="{{ route('admin.roles.permissions.update', $role->id) }}" method="POST">
                    @csrf

                    @php
                        // Group permissions by module
                        $groupedPermissions = $permissions->groupBy(function ($perm) {
                            $parts = explode(' ', $perm->name);
                            return $parts[0];
                        });
                    @endphp

                    @foreach ($groupedPermissions as $module => $modulePermissions)
                        <div class="mb-4">
                            <h5 class="mb-2 text-primary">{{ Str::title($module) }}</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($modulePermissions as $permission)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                            value="{{ $permission->id }}" id="permission-{{ $permission->id }}"
                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="permission-{{ $permission->id }}">
                                            {{ $module === 'view' && $permission->name === 'view dashboard'
                                                ? 'View Dashboard'
                                                : Str::title(str_replace($module . ' ', '', $permission->name)) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-success mt-3">Update Permissions</button>
                </form>
            </div>
        </div>
    </div>
    </section>
</div>
@endsection
