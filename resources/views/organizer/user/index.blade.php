@extends('organizer.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Users') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('organizer.dashboard')}}">
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
        <a href="#">{{ __('Users') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Users') }}</div>
            </div>

            <div class="col-lg-8 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i> {{ __('Add User') }}</a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($users) == 0)
                <h3 class="text-center">{{ __('NO USERS FOUND!') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Email') }}</th>
                        <th scope="col">{{ __('Role') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php $serial = 1; ?>

                      @foreach($users as $user)
                        <tr>
                          <td>{{$serial}}</td>
                          <td>{{$user->name}}</td>
                          <td>{{$user->email}}</td>
                          <td><?php echo $users = \App\Models\RolePermission::where('id',$user->role_id)->first('name')->name; ?> </td>
                          <td>
                            <a class="btn btn-secondary btn-sm" href="{{ route('organizer.organizer_management.user.edit',['id' => $user->id]) }}">
                              <i class="fas fa-edit"></i>
                            </a>
                            
                            <form class="deleteForm d-inline-block" action="{{ route('organizer.organizer_management.user.destroy', ['id' => $user->id]) }}" method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn">
                                <i class="fas fa-trash"></i>
                              </button>
                            </form>
                          </td>
                        </tr>
                        <?php $serial++; ?>
                        @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer"></div>
      </div>
    </div>
  </div>

  {{-- create modal --}}
  @include('organizer.user.create')


@endsection
