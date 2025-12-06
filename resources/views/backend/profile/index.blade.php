@extends('backend.master')
@section('title', 'Softvence Food Team')
@section('content')

    <div class="page-content d-flex justify-content-center align-items-center text-dark">
        <section class="row g-4 w-100 d-flex justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="d-flex flex-column justify-content-between h-100">

                                    <div>
                                        <h4 class="text-dark">Profile Information</h4>
                                        <p class="pb-2 text-dark">
                                            Update your account's profile information and email address.
                                        </p>
                                    </div>

                                    <form action="{{ route('admin.profile.update', $users->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @role('employee')
                                        <div class="mb-3">
                                            <label class="form-label text-dark">Employee ID</label>
                                            <input type="text" name="employee_code" class="form-control py-2" value="{{ $users->employee_code }}"
                                                placeholder="Input your employee ID">
                                        </div>
                                        @endrole
                                        <div class="mb-3">
                                            <label class="form-label text-dark">Name</label>
                                            <input type="text" name="name" class="form-control py-2" value="{{ $users->name }}"
                                                placeholder="Enter name">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-dark">Email</label>
                                            <input type="email" name="email" class="form-control py-2" value="{{ $users->email}}"
                                                placeholder="Enter email">
                                        </div>
                                        @role('employee')
                                        <div class="mb-3">
                                            <label class="form-label text-dark">Team</label>
                                            <select class="form-select" name="team_id">
                                                <option value="">selecte team</option>
                                                @foreach ($teams as $team)
                                                <option @if ($team->id == $users->team_id) selected @endif value="{{$team->id}}">{{$team->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endrole
                                        <div class="mb-3">
                                            <label class="form-label text-dark">image</label>
                                            <input type="file" name="image" class="form-control"  value="{{ $users->image}}"
                                                placeholder="Enter email">
                                        </div>
                                        <div class="mb-3">
                                          <input type="submit" class="btn btn-success " value="save">
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="page-content d-flex justify-content-center align-items-center text-dark mt-5 mb-5">
        <section class="row g-4 w-100 d-flex justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="d-flex flex-column justify-content-between h-100">

                                    <div>
                                        <h4 class="text-dark">Update Password</h4>
                                        <p class="pb-2 text-dark">
                                            Ensure your account is using a long, random password to stay secure.
                                        </p>
                                    </div>

                                    <form action="{{ route('admin.profile.updatePassword') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label text-dark">Current Password</label>
                                            <input type="password" name="current_password" class="form-control py-2"
                                                placeholder="Current password">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-dark">New Password</label>
                                            <input type="password" name="new_password" class="form-control py-2"
                                                placeholder="New password">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label text-dark">Confirm Password</label>
                                            <input type="password" name="new_password_confirmation" class="form-control py-2"
                                                placeholder="Confirm password">
                                        </div>
                                        <div class="mb-3">
                                          <input type="submit" class="btn btn-success " value="save">
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </div>




@endsection
