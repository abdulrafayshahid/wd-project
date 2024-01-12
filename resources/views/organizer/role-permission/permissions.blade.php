@extends('organizer.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Permissions') }}</h4>
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
        <a href="#">{{ __('Role') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Permissions') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('organizer.organizer_management.role.update_permissions', ['id' => $role->id]) }}" method="post">
          @csrf
          <div class="card-header">
            <div class="card-title d-inline-block">{{ __('Permissions of') . ' ' . $role->name }}</div>
            <a class="btn btn-info btn-sm float-right d-inline-block"
              href="{{ route('organizer.organizer_management.role_permissions') }}">
              <span class="btn-label">
                <i class="fas fa-backward"></i>
              </span>
              {{ __('Back') }}
            </a>
          </div>

          <div class="card-body py-5">
            <div class="row justify-content-center">
              <div class="col-lg-5">
                <div class="alert alert-warning text-center" role="alert">
                  <strong class="text-dark">{{ __('Select from this below options.') }}</strong>
                </div>
              </div>
            </div>

            @php $rolePermissions = json_decode($role->permissions); @endphp
            <div class="row mt-3 justify-content-center">
              <div class="col-lg-10">
                <div class="form-group">
                  <div class="selectgroup selectgroup-pills">

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Organizer Dashboard"
                        @if (is_array($rolePermissions) && in_array('Organizer Dashboard', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Organizer Dashboard') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Event Management"
                        @if (is_array($rolePermissions) && in_array('Event Management', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Event Management') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Event Bookings"
                        @if (is_array($rolePermissions) && in_array('Event Bookings', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Event Bookings') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Withdraw"
                        @if (is_array($rolePermissions) && in_array('Withdraw', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Withdraw') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Transaction"
                        @if (is_array($rolePermissions) && in_array('Transaction', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Transaction') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Pwa Scanner"
                        @if (is_array($rolePermissions) && in_array('Pwa Scanner', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Pwa Scanner') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Support Ticket"
                        @if (is_array($rolePermissions) && in_array('Support Ticket', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Support Tickets') }}</span>
                    </label>

                  </div>
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
