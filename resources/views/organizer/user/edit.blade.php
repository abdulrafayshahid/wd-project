@extends('organizer.layout')

@section('content')
<div class="page-header">
    <h4 class="page-title">{{ __('User Edit') }}</h4>
    <ul class="breadcrumbs">
        <li class="nav-home">
            <a href="{{ route('organizer.dashboard') }}">
                <i class="flaticon-home"></i>
            </a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">{{ __('Dashboard') }}</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">{{ __('User Edit') }}</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>

    </ul>
</div>
<?php $roles = \App\Models\RolePermission::where('user_id',auth()->user()->id)->get(); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <form action="{{ route('organizer.organizer_management.user.update', ['id' => $user->id]) }}"
                method="post">
                @csrf
                <div class="card-header">
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('organizer.organizer_management.user') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ __('Back') }}
                    </a>
                </div>
                <div class="card-body py-5">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">{{ __('Name') . '*' }}</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter User Name" value="{{ $user->name }}">
                                <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Email') . '*' }}</label>
                                <input type="text" class="form-control" name="email" placeholder="Enter User Email" value="{{ $user->email }}">
                                <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Password') . '*' }}</label>
                                <input type="password" class="form-control" name="password"
                                    placeholder="Enter User Password">
                                <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Role') . '*' }}</label>
                                <select name="role_id" class="form-control">
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                    <option @if($user->role_id == $role->id) {{ "selected" }} @endif value="{{ $role->id }}">{{ $role->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection