@extends('organizer.layout')

@section('content')

  <div class="page-header">
    <h4 class="page-title">{{ __('All Manual Tickets') }}</h4>
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
        <a href="#">{{ __('Manual Tickets') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('All Manual Tickets') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">
                {{ __('All Manual Tickets') }}
              </div>
            </div>

            <div class="col-lg-8 mt-2 mt-lg-0">
              <button class="btn btn-danger float-lg-right float-none btn-sm ml-2 mt-1 bulk-delete d-none"
                data-href="{{ route('organizer.manual.ticket.bulk_delete') }}"><i class="flaticon-interface-5"></i>
                Delete</button>

              <form action="" class="float-lg-right float-none" method="GET">
                <input type="text" name="ticket_id" class="form-control min-w-250" placeholder="Search by Ticket ID"
                  value="{{ !empty(request()->input('ticket_id')) ? request()->input('ticket_id') : '' }}">
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
  
              @if (session()->has('course_status_warning'))
                <div class="alert alert-warning">
                  <p class="text-dark mb-0">{{ session()->get('course_status_warning') }}</p>
                </div>
              @endif
              @if (count($tickets) == 0)
                <h3 class="text-center mt-2">{{ __('NO MANUAL TICKETS FOUND ') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Ticket ID') }}</th>
                        <th scope="col">{{ __('Email') }}</th>
                        <th scope="col">{{ __('Subject') }}</th>
                        <th scope="col">{{ __('Mail') }}</th>
                        <th scope="col">{{ __('Action') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($tickets as $item)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $item->id }}">
                          </td>
                          <td>
                            {{ $item->id }}
                          </td>
                          <td>
                            {{ $item->customer_email != '' ? $item->customer_email : '-' }}
                          </td>
                          <td>
                            {{ $item->customer_name }}
                          </td>
                          <td>
                            <a  class="btn btn-sm btn-dark" href="{{ route('organizer.manual.ticket.sendmail_manual',$id = $item->id) }}">Send Mail</a>
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('organizer.manual.ticket.view', $item->id) }}"
                                  class="dropdown-item">
                                  {{ __('View') }}
                                </a>
                                <a href="{{ route('organizer.manual.ticket.edit', $item->id) }}"
                                  class="dropdown-item">
                                  {{ __('Edit') }}
                                </a>
                                <form class="deleteForm d-block"
                                  action="{{ route('organizer.manual.ticket.delete', $item->id) }}" method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                              </div>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer">
          {{ $tickets->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection
