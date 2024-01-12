@extends('backend.layout')

@section('content')
<style>
  .table td, .table th {
    font-size: 14px;
    border-top-width: 0px;
    border-bottom: 1px solid;
    border-color: #ebedf2 !important;
    padding: 0 20px !important;
    height: 60px;
    vertical-align: middle !important;
  }
</style>
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Event') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Events Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a
          href="{{ route('admin.choose-event-type', ['language' => $defaultLang->code]) }}">{{ __('Choose Event Type') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Event') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add Event') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('admin.event_management.event', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-10 offset-lg-1">
              <div class="alert alert-danger pb-1 dis-none" id="eventErrors">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>
              <div class="col-lg-12">
                <label for="" class="mb-2"><strong>Gallery Images **</strong></label>
                <form action="{{ route('admin.event.imagesstore') }}" id="my-dropzone" enctype="multipart/formdata"
                  class="dropzone create">
                  @csrf
                  <div class="fallback">
                    <input name="file" type="file" multiple />
                  </div>
                </form>
                <div class=" mb-0" id="errpreimg">

                </div>
                <p class="text-warning">{{ __('Image Size : 1170 x 570') }}</p>
              </div>
              <form id="eventForm" action="{{ route('admin.event_management.store_event') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="event_type" value="{{ request()->input('type') }}">
                <div class="form-group">
                  <label for="">{{ __('Thumbnail Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="thumbnail">
                    </div>
                  </div>
                  <p class="text-warning">{{ __('Image Size : 320x230') }}</p>
                </div>

                {{-- <div class="row event_type_row">
                  <div class="col-lg-12">
                    <div class="form-group mt-1">
                      <label for="">{{ __('Date Type') . '*' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="date_type" value="single" class="selectgroup-input eventDateType"
                            checked>
                          <span class="selectgroup-button">{{ __('Single') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="date_type" value="multiple" class="selectgroup-input eventDateType">
                          <span class="selectgroup-button">{{ __('Multiple') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div> --}}

                <div class="row event_type_row2">
                  <div class="col-lg-12">
                    <div class="form-group mt-1">
                      <label for="">{{ __('Event Type') . '*' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="date_type" value="single" class="selectgroup-input eventDateType2"
                            checked>
                          <span class="selectgroup-button">{{ __('Single') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="date_type" value="multiple" class="selectgroup-input eventDateType2">
                          <span class="selectgroup-button">{{ __('Multiple') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row countDownStatus">
                  <div class="col-lg-12">
                    <div class="form-group mt-1">
                      <label for="">{{ __('Countdown Status') . '*' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="countdown_status" value="1" class="selectgroup-input" checked>
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="countdown_status" value="0" class="selectgroup-input">
                          <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row" id="single_dates">
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('Start Date') . '*' }}</label>
                      <input type="date" name="start_date" placeholder="Enter Start Date" class="form-control">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label for="">{{ __('Start Time') . '*' }}</label>
                      <input type="time" name="start_time" class="form-control">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label>{{ __('End Date') . '*' }}</label>
                      <input type="date" name="end_date" placeholder="Enter End Date" class="form-control">
                    </div>
                  </div>

                  <div class="col-lg-3">
                    <div class="form-group">
                      <label for="">{{ __('End Time') . '*' }}</label>
                      <input type="time" name="end_time" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row">
                  {{-- <div class="col-lg-12 d-none" id="multiple_dates">
                    <div class="form-group">
                      <table class="table table-bordered ">
                        <thead>
                          <tr>
                            <th>{{ __('Start Date') }}</th>
                            <th>{{ __('Start Time') }}</th>
                            <th>{{ __('End Date') }}</th>
                            <th>{{ __('End Time') }}</th>
                            <th><a href="javascrit:void(0)" class="btn btn-success addDateRow"><i class="fas fa-plus-circle"></i></a></th>
                          </tr>
                        <tbody>
                          <tr>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('Start Date') . '*' }}</label>
                                <input type="date" name="m_start_date[]" class="form-control">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('Start Time') . '*' }}</label>
                                <input type="time" name="m_start_time[]" class="form-control">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('End Date') . '*' }} </label>
                                <input type="date" name="m_end_date[]" class="form-control">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('End Time') . '*' }} </label>
                                <input type="time" name="m_end_time[]" class="form-control">
                              </div>
                            </td>
                            <td>
                              <a href="javascript:void(0)" class="btn btn-danger deleteDateRow">
                                <i class="fas fa-minus"></i></a>
                            </td>
                          </tr>
                        </tbody>
                        </thead>
                      </table>
                    </div>
                  </div> --}}
                  <div class="col-lg-12 d-none" id="multiple_dates2">
                    <div class="form-group">
                      <table class="table table-bordered ">
                        <thead>
                          <tr>
                            <th colspan="5">{{ __('Start Date') }}</th>
                          </tr>
                          <tr>
                            <th colspan="5">
                              <div class="form-group">
                              <input type="date" class="form-control start_date">
                              </div>
                            </th>
                          </tr>
                        <tbody>
                          <tr>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('Day') . '*' }}</label>
                                <input type="text" class="form-control show_date" readonly>
                                <input type="hidden" name="m_start_date[]" class="form-control hidden_date">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('Start Time') . '*' }}</label>
                                <input type="time" name="m_start_time[]" class="form-control">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('End Date') . '*' }} </label>
                                <input type="date" name="m_end_date[]" class="form-control">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <label for="">{{ __('End Time') . '*' }} </label>
                                <input type="time" name="m_end_time[]" class="form-control">
                              </div>
                            </td>
                            <td>
                              <a href="javascrit:void(0)" class="btn btn-success addDateRow2"><i
                                class="fas fa-plus-circle"></i></a>
                              <a href="javascript:void(0)" class="btn btn-danger deleteDateRow">
                                <i class="fas fa-minus"></i></a>
                            </td>
                          </tr>
                        </tbody>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div>

                <div class="row ">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{ __('Status') . '*' }}</label>
                      <select name="status" class="form-control">
                        <option selected disabled>{{ __('Select a Status') }}</option>
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Deactive') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{ __('Is Feature') . '*' }}</label>
                      <select name="is_featured" class="form-control is_featured">
                        <option selected disabled>{{ __('Select') }}</option>
                        <option value="yes">{{ __('Yes') }}</option>
                        <option value="no">{{ __('No') }}</option>
                      </select>
                      <div class="featured_day mt-2">
                        <label for="">{{ __('How Many Days Is Feature') . '*' }}</label>
                        <input type="number" name="" class="form-control featured_day_input" placeholder="0">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{ __('Organizer') }}</label>
                      <select name="organizer_id" class="form-control js-example-basic-single">
                        <option selected value="">{{ __('Select Organizer') }}</option>
                        @foreach ($organizers as $organizer)
                          <option value="{{ $organizer->id }}">{{ $organizer->username }}</option>
                        @endforeach
                      </select>
                      <p class="text-warning">{{ __("Please leave it blank for Admin's event") }}</p>
                    </div>
                  </div>
                  @if (request()->input('type') == 'venue')




                  @endif
                </div>
                @if (request()->input('type') == 'online')
                  {{-- /*****--Ticekt limtit & ticket for each customer start--****** --}}

                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group mt-1">
                        <label for="">{{ __('Total Number of Available Tickets') . '*' }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="ticket_available_type" value="unlimited"
                              class="selectgroup-input" checked>
                            <span class="selectgroup-button">{{ __('Unlimited') }}</span>
                          </label>

                          <label class="selectgroup-item">
                            <input type="radio" name="ticket_available_type" value="limited"
                              class="selectgroup-input">
                            <span class="selectgroup-button">{{ __('Limited') }}</span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 d-none" id="ticket_available">
                      <div class="form-group">
                        <label>{{ __('Enter total number of available tickets') . '*' }}</label>
                        <input type="number" name="ticket_available"
                          placeholder="Enter total number of available tickets" class="form-control">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group mt-1">
                        <label for="">{{ __('Maximum number of tickets for each customer') . '*' }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="max_ticket_buy_type" value="unlimited"
                              class="selectgroup-input" checked>
                            <span class="selectgroup-button">{{ __('Unlimited') }}</span>
                          </label>

                          <label class="selectgroup-item">
                            <input type="radio" name="max_ticket_buy_type" value="limited"
                              class="selectgroup-input">
                            <span class="selectgroup-button">{{ __('Limited') }}</span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 d-none" id="max_buy_ticket">
                      <div class="form-group">
                        <label>{{ __('Enter Maximum number of tickets for each customer') . '*' }}</label>
                        <input type="number" name="max_buy_ticket"
                          placeholder="Enter Maximum number of tickets for each customer" class="form-control">
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="">
                        <div class="form-group">
                          <label for="">{{ __('Price') }} ({{ $getCurrencyInfo->base_currency_text }}) *
                          </label>
                          <input type="number" name="price" id="ticket-pricing" class="form-control"
                            placeholder="Enter Ticket Price">
                        </div>
                      </div>
                      <div class="form-group">
                        <input type="checkbox" name="pricing_type" value="free" class="" id="free_ticket">
                        <label for="free_ticket">{{ __('Tickets are Free') }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="row" id="early_bird_discount_free">
                    <div class="col-lg-12">
                      <div class="form-group mt-1">
                        <label for="">{{ __('Early Bird Discount') . '*' }}</label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="early_bird_discount_type" value="disable"
                              class="selectgroup-input" checked>
                            <span class="selectgroup-button">{{ __('Disable') }}</span>
                          </label>

                          <label class="selectgroup-item">
                            <input type="radio" name="early_bird_discount_type" value="enable"
                              class="selectgroup-input">
                            <span class="selectgroup-button">{{ __('Enable') }}</span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12 d-none" id="early_bird_dicount">
                      <div class="row">
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label for="">{{ __('Discount') }} * </label>
                            <select name="discount_type" class="form-control">
                              <option disabled>{{ __('Select Discount Type') }}</option>
                              <option value="fixed">{{ __('Fixed') }}</option>
                              <option value="percentage">{{ __('Percentage') }}</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label for="">{{ __('Amount') }} * </label>
                            <input type="number" name="early_bird_discount_amount" class="form-control">
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label for="">{{ __('Discount End Date') }} *</label>
                            <input type="date" name="early_bird_discount_date" class="form-control">
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label for="">{{ __('Discount End Time') }} *</label>
                            <input type="time" name="early_bird_discount_time" class="form-control">
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                @endif


                <div id="accordion" class="mt-3">
                  @foreach ($languages as $language)
                    <div class="version">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button" class="btn btn-link" data-toggle="collapse"
                            data-target="#collapse{{ $language->id }}"
                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . __(' Language') }} {{ $language->is_default == 1 ? '(Default)' : '' }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ $language->id }}"
                        class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        <div class="version-body">
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Event Title') . '*' }}</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="Enter Event Name">
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $categories = DB::table('event_categories')
                                      ->where('language_id', $language->id)
                                      ->where('status', 1)
                                      ->orderBy('serial_number', 'asc')
                                      ->get();
                                @endphp

                                <label for="">{{ __('Category') . '*' }}</label>
                                <select name="{{ $language->code }}_category_id" class="form-control">
                                  <option selected disabled>{{ __('Select Category') }}</option>

                                  @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>

                          @if (request()->input('type') == 'venue')
                            <div class="row">
                              <div class="col-lg-12">
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                  <label for="">{{ __('Address') . '*' }}</label>
                                  <input type="text" id="location" name="{{ $language->code }}_address" class="address form-control" placeholder="Enter Address">
                                  <input type="hidden" value="22" name="longitude"
                                  class="lng" id="lng">
                                  <input type="hidden" value="22" name="latitude"
                                  class="lat" id="lat">
                              </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                  <label for="">{{ __('County') . '*' }}</label>
                                  <select name="{{ $language->code }}_country"
                                  id="country" class="form-control">
                                  <option>select country</option>
                                  <option value="Afghanistan">Afghanistan</option>
                                  <option value="Aland Islands">Aland Islands</option>
                                  <option value="Albania">Albania</option>
                                  <option value="Algeria">Algeria</option>
                                  <option value="American Samoa">American Samoa</option>
                                  <option value="Andorra">Andorra</option>
                                  <option value="Angola">Angola</option>
                                  <option value="Anguilla">Anguilla</option>
                                  <option value="Antarctica">Antarctica</option>
                                  <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                  <option value="Argentina">Argentina</option>
                                  <option value="Armenia">Armenia</option>
                                  <option value="Aruba">Aruba</option>
                                  <option value="Australia">Australia</option>
                                  <option value="Austria">Austria</option>
                                  <option value="Azerbaijan">Azerbaijan</option>
                                  <option value="Bahamas">Bahamas</option>
                                  <option value="Bahrain">Bahrain</option>
                                  <option value="Bangladesh">Bangladesh</option>
                                  <option value="Barbados">Barbados</option>
                                  <option value="Belarus">Belarus</option>
                                  <option value="Belgium">Belgium</option>
                                  <option value="Belize">Belize</option>
                                  <option value="Benin">Benin</option>
                                  <option value="Bermuda">Bermuda</option>
                                  <option value="Bhutan">Bhutan</option>
                                  <option value="Bolivia">Bolivia</option>
                                  <option value="Bonaire,  Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba
                                  </option>
                                  <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                  <option value="Botswana">Botswana</option>
                                  <option value="Bouvet Island">Bouvet Island</option>
                                  <option value="Brazil">Brazil</option>
                                  <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                  <option value="Brunei Darussalam">Brunei Darussalam</option>
                                  <option value="Bulgaria Bulgaria">Bulgaria</option>
                                  <option value="Burkina Faso">Burkina Faso</option>
                                  <option value="Burundi">Burundi</option>
                                  <option value="Cambodia">Cambodia</option>
                                  <option value="Cameroon">Cameroon</option>
                                  <option value="Canada">Canada</option>
                                  <option value="Cape">Cape Verde</option>
                                  <option value="Cayman Islands">Cayman Islands</option>
                                  <option value="Central African Republic">Central African Republic</option>
                                  <option value="Chad">Chad</option>
                                  <option value="Chile">Chile</option>
                                  <option value="China">China</option>
                                  <option value="Christmas Island">Christmas Island</option>
                                  <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                  <option value="Colombia">Colombia</option>
                                  <option value="Comoros">Comoros</option>
                                  <option value="Congo">Congo</option>
                                  <option value="Congo, Democratic Republic of the Congo">Congo, Democratic Republic of
                                    the Congo</option>
                                  <option value="Cook Islands">Cook Islands</option>
                                  <option value="Costa Rica">Costa Rica</option>
                                  <option value="Cote D'Ivoire">Cote D'Ivoire</option>
                                  <option value="Croatia">Croatia</option>
                                  <option value="Cuba">Cuba</option>
                                  <option value="Curacao">Curacao</option>
                                  <option value="Cyprus">Cyprus</option>
                                  <option value="Czech Republic">Czech Republic</option>
                                  <option value="Denmark">Denmark</option>
                                  <option value="Djibouti">Djibouti</option>
                                  <option value="Dominica">Dominica</option>
                                  <option value="Dominican Republic">Dominican Republic</option>
                                  <option value="Ecuador">Ecuador</option>
                                  <option value="Egypt">Egypt</option>
                                  <option value="El Salvador">El Salvador</option>
                                  <option value="Equatorial Guinea">Equatorial Guinea</option>
                                  <option value="Eritrea">Eritrea</option>
                                  <option value="Estonia">Estonia</option>
                                  <option value="Ethiopia">Ethiopia</option>
                                  <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                  <option value="Faroe Islands">Faroe Islands</option>
                                  <option value="Fiji">Fiji</option>
                                  <option value="Finland">Finland</option>
                                  <option value="France">France</option>
                                  <option value="French Guiana">French Guiana</option>
                                  <option value="French Polynesia">French Polynesia</option>
                                  <option value="French Southern Territories">French Southern Territories</option>
                                  <option value="Gabon">Gabon</option>
                                  <option value="Gambia">Gambia</option>
                                  <option value="Georgia">Georgia</option>
                                  <option value="Germany">Germany</option>
                                  <option value="Ghana">Ghana</option>
                                  <option value="Gibraltar">Gibraltar</option>
                                  <option value="Greece">Greece</option>
                                  <option value="Greenland">Greenland</option>
                                  <option value="Grenada">Grenada</option>
                                  <option value="Guadeloupe">Guadeloupe</option>
                                  <option value="Guam">Guam</option>
                                  <option value="Guatemala">Guatemala</option>
                                  <option value="Guernsey">Guernsey</option>
                                  <option value="Guinea">Guinea</option>
                                  <option value="Guinea-Bissau">Guinea-Bissau</option>
                                  <option value="Guyana">Guyana</option>
                                  <option value="Haiti">Haiti</option>
                                  <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands
                                  </option>
                                  <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                  <option value="Honduras">Honduras</option>
                                  <option value="Hong Kong">Hong Kong</option>
                                  <option value="Hungary">Hungary</option>
                                  <option value="Iceland">Iceland</option>
                                  <option value="India">India</option>
                                  <option value="Indonesia">Indonesia</option>
                                  <option value="Iran Islamic Republic of">Iran, Islamic Republic of</option>
                                  <option value="Iraq">Iraq</option>
                                  <option value="Ireland">Ireland</option>
                                  <option value="Isle">Isle of Man</option>
                                  <option value="Israel">Israel</option>
                                  <option value="Italy">Italy</option>
                                  <option value="Jamaica">Jamaica</option>
                                  <option value="Japan">Japan</option>
                                  <option value="Jersey">Jersey</option>
                                  <option value="Jordan">Jordan</option>
                                  <option value="Kazakhstan">Kazakhstan</option>
                                  <option value="Kenya">Kenya</option>
                                  <option value="Kiribati">Kiribati</option>
                                  <option value="Korea Democratic People's Republic of">Korea, Democratic People's
                                    Republic of</option>
                                  <option value="Korea Korea, Republic of">Korea, Republic of</option>
                                  <option value="Kosovo">Kosovo</option>
                                  <option value="Kuwait">Kuwait</option>
                                  <option value="Kyrgyzstan">Kyrgyzstan</option>
                                  <option value="Lao People's Democratic Republic">Lao People's Democratic Republic
                                  </option>
                                  <option value="Latvia">Latvia</option>
                                  <option value="Lebanon">Lebanon</option>
                                  <option value="Lesotho">Lesotho</option>
                                  <option value="Liberia">Liberia</option>
                                  <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                  <option value="Liechtenstein">Liechtenstein</option>
                                  <option value="Lithuania">Lithuania</option>
                                  <option value="Luxembourg">Luxembourg</option>
                                  <option value="Macao">Macao</option>
                                  <option value="Macedonia  the Former Yugoslav Republic of">Macedonia, the Former
                                    Yugoslav Republic of</option>
                                  <option value="Madagascar">Madagascar</option>
                                  <option value="Malawi">Malawi</option>
                                  <option value="Malaysia">Malaysia</option>
                                  <option value="Maldives">Maldives</option>
                                  <option value="Mali">Mali</option>
                                  <option value="Malta">Malta</option>
                                  <option value="Marshall">Marshall Islands</option>
                                  <option value="Martinique">Martinique</option>
                                  <option value="Mauritania">Mauritania</option>
                                  <option value="Mauritius">Mauritius</option>
                                  <option value="Mayotte">Mayotte</option>
                                  <option value="Mexico">Mexico</option>
                                  <option value="Micronesia Federated States of">Micronesia, Federated States of
                                  </option>
                                  <option value="Moldova, Republic of">Moldova, Republic of</option>
                                  <option value="Monaco">Monaco</option>
                                  <option value="Mongolia">Mongolia</option>
                                  <option value="Montenegro">Montenegro</option>
                                  <option value="Montserrat">Montserrat</option>
                                  <option value="Morocco">Morocco</option>
                                  <option value="Mozambique">Mozambique</option>
                                  <option value="Myanmar">Myanmar</option>
                                  <option value="Namibia">Namibia</option>
                                  <option value="Nauru">Nauru</option>
                                  <option value="Nepal">Nepal</option>
                                  <option value="Netherlands">Netherlands</option>
                                  <option value="Netherlands Antilles">Netherlands Antilles</option>
                                  <option value="New Caledonia">New Caledonia</option>
                                  <option value="New Zealand">New Zealand</option>
                                  <option value="Nicaragua">Nicaragua</option>
                                  <option value="Niger">Niger</option>
                                  <option value="Nigeria">Nigeria</option>
                                  <option value="Niue">Niue</option>
                                  <option value="Norfolk Island">Norfolk Island</option>
                                  <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                  <option value="Norway">Norway</option>
                                  <option value="Oman">Oman</option>
                                  <option value="Pakistan">Pakistan</option>
                                  <option value="Palau">Palau</option>
                                  <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied
                                  </option>
                                  <option value="Panama">Panama</option>
                                  <option value="Papua New Guinea">Papua New Guinea</option>
                                  <option value="Paraguay">Paraguay</option>
                                  <option value="Peru">Peru</option>
                                  <option value="Philippines">Philippines</option>
                                  <option value="Pitcairn">Pitcairn</option>
                                  <option value="Poland">Poland</option>
                                  <option value="Portugal">Portugal</option>
                                  <option value="Puerto">Puerto Rico</option>
                                  <option value="Qatar">Qatar</option>
                                  <option value="Reunion">Reunion</option>
                                  <option value="Romania">Romania</option>
                                  <option value="Russian Federation">Russian Federation</option>
                                  <option value="Rwanda">Rwanda</option>
                                  <option value="Saint Barthelemy">Saint Barthelemy</option>
                                  <option value="Saint Helena">Saint Helena</option>
                                  <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                  <option value="Saint Lucia">Saint Lucia</option>
                                  <option value="Saint Martin">Saint Martin</option>
                                  <option value="Saint  Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                  <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines
                                  </option>
                                  <option value="Samoa">Samoa</option>
                                  <option value="San Marino">San Marino</option>
                                  <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                  <option value="Saudi Arabia">Saudi Arabia</option>
                                  <option value="Senegal">Senegal</option>
                                  <option value="Serbia">Serbia</option>
                                  <option value="Serbia  and Montenegro">Serbia and Montenegro</option>
                                  <option value="Seychelles">Seychelles</option>
                                  <option value="Sierra Leone">Sierra Leone</option>
                                  <option value="Singapore">Singapore</option>
                                  <option value="Sint Maarten">Sint Maarten</option>
                                  <option value="Slovakia">Slovakia</option>
                                  <option value="Slovenia">Slovenia</option>
                                  <option value="Solomon Islands">Solomon Islands</option>
                                  <option value="Somalia">Somalia</option>
                                  <option value="South Africa">South Africa</option>
                                  <option value="South  Georgia and the South Sandwich Islands">South Georgia and the
                                    South Sandwich Islands</option>
                                  <option value="South Sudan">South Sudan</option>
                                  <option value="Spain">Spain</option>
                                  <option value="Sri Lanka">Sri Lanka</option>
                                  <option value="Sudan">Sudan</option>
                                  <option value="Suriname">Suriname</option>
                                  <option value="Svalbard  and Jan Mayen">Svalbard and Jan Mayen</option>
                                  <option value="Swaziland">Swaziland</option>
                                  <option value="Sweden">Sweden</option>
                                  <option value="Switzerland">Switzerland</option>
                                  <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                  <option value="Taiwan  Province of China">Taiwan, Province of China</option>
                                  <option value="Tajikistan">Tajikistan</option>
                                  <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                  <option value="Thailand">Thailand</option>
                                  <option value="Timor -Leste">Timor-Leste</option>
                                  <option value="Togo">Togo</option>
                                  <option value="Tokelau">Tokelau</option>
                                  <option value="Tonga">Tonga</option>
                                  <option value="Trinidad  and Tobago">Trinidad and Tobago</option>
                                  <option value="Tunisia">Tunisia</option>
                                  <option value="Turkey">Turkey</option>
                                  <option value="Turkmenistan">Turkmenistan</option>
                                  <option value="Turks  and Caicos Islands">Turks and Caicos Islands</option>
                                  <option value="Tuvalu">Tuvalu</option>
                                  <option value="Uganda">Uganda</option>
                                  <option value="Ukraine">Ukraine</option>
                                  <option value="United Arab Emirates">United Arab Emirates</option>
                                  <option value="United Kingdom">United Kingdom</option>
                                  <option value="United States">United States</option>
                                  <option value="United States Minor Outlying Islands">United States Minor Outlying
                                    Islands</option>
                                  <option value="Uruguay">Uruguay</option>
                                  <option value="Uzbekistan">Uzbekistan</option>
                                  <option value="Vanuatu">Vanuatu</option>
                                  <option value="Venezuela">Venezuela</option>
                                  <option value="Viet Nam">Viet Nam</option>
                                  <option value="Virgin Islands, British">Virgin Islands, British</option>
                                  <option value="Virgin Islands, U.s.">Virgin Islands, U.s.</option>
                                  <option value="Wallis and Futuna">Wallis and Futuna</option>
                                  <option value="Western Sahara">Western Sahara</option>
                                  <option value="Yemen">Yemen</option>
                                  <option value="Zambia">Zambia</option>
                                  <option value="Zimbabwe">Zimbabwe</option>
                                </select>

                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                  <label for="">{{ __('Sate') }}</label>
                                  <input type="text" id="state" name="{{ $language->code }}_state"
                                    class="form-control"
                                    placeholder="Enter State">
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                  <label for="">{{ __('City') . '*' }}</label>
                                  <input type="text" id="city" name="{{ $language->code }}_city"
                                    class="form-control"
                                    placeholder="Enter City">
                                </div>
                              </div>
                              <div class="col-lg-4">
                                <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                  <label for="">{{ __('Zip/Post Code ') }}</label>
                                  <input type="text" placeholder="Enter Zip/Post Code" id="zip"
                                    name="{{ $language->code }}_zip_code"
                                    class="form-control">
                                </div>
                              </div>
                            </div>
                          @endif

                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Description') . '*' }}</label>
                                <textarea id="descriptionTmce{{ $language->id }}" class="form-control summernote"
                                  name="{{ $language->code }}_description" data-height="300"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Transportation')  }}</label>
                                <br>
                                <span><b>{{ __('Is transportation used for this activity?')}}</b>
                                              <br>
                                              <p>{{__("Specify if transportation is used during the activity. Transport related to pickup or drop-off services can be added later.")}}</p></span>

                              </div>
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }} ml-4">
                                <input type="radio" name="{{$language->code}}_transport_required" class="transport_yes"
                                       class="form-check-input">
                                <label class="form-check-label" for="{{$language->code}}_transport_yes">Yes</label>
                              </div>
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }} ml-4">
                                <input type="radio" name="{{$language->code}}_transport_required" class="transport_no"
                                       class="form-check-input">
                                <label class="form-check-label" for="t{{$language->code}}_transport_no">No</label>
                              </div>
                              <div class="fetch-entry"></div>
                              <div class="form-group ml-4 d-none add-entry">
                                <button data-toggle="modal" data-target="#addEntryModal{{$language->code}}" onclick="resetItem('{{$language->code}}')"
                                        class="btn btn-outline-secondary btn-sm text-success rounded border border-primary">
                                  {{__('Add Entry')}}</button>
                              </div>

                            </div>
                          </div>


     {{-- mn model start --}}



     <div class="row">
      <div class="col">
        <div class="form-group" data-direction="{{ $language->direction }}">
            <label>{{ __('Guide & activity info') }}</label>
            <br>
            <span><b>{{ __('Who will your customers mainly interact with during your activity?') }}</b></span>
        </div>

        <!-- Option 1: Nobody -->
        <div class="form-group option" data-direction="{{ $language->direction }}">
            <input type="radio" value="Nobody" name="activity_required" class="activity_yes">
            <label class="form-check-label">Nobody</label>
        </div>

        <!-- Option 2: Tour guide -->
        <div class="form-group option" data-direction="{{ $language->direction }}">
            <input type="radio"  value="Tour_guide" name="activity_required" class="activity_no">
            <label class="form-check-label">Tour guide</label>
            <br>
            <span><b>{{ __('Leads a group of customers through a tour and explains things about the destination/attraction.') }}</b></span>
        </div>

        <!-- Option 3: Host or greeter -->
        <div class="form-group option" data-direction="{{ $language->direction }}">
            <input type="radio"  value="Host_or_greeter" name="activity_required" class="activity_no">
            <label class="form-check-label">Host or greeter</label>
            <br>
            <span><b>{{ __('Provides guidance in the form of purchasing a ticket and waiting in line with customers, but doesn’t provide a full guided tour of the attraction. A greeter might give an introduction to an activity.') }}</b></span>
        </div>

        <!-- Option 4: Instructor -->
        <div class="form-group option" data-direction="{{ $language->direction }}">
            <input type="radio"   value="Instructor" name="activity_required" class="activity_no">
            <label class="form-check-label">Instructor</label>
            <br>
            <span><b>{{ __('Shows customers how to use equipment or teaches them how to do something') }}</b></span>
        </div>

        <!-- Option 5: Driver only -->
        <div class="form-group option" data-direction="{{ $language->direction }}">
            <input type="radio" value="Driver_only"name="activity_required" class="activity_no">
            <label class="form-check-label">Driver only</label>
            <br>
            <span><b>{{ __('Drives the customer somewhere but doesn’t explain anything along the way.') }}</b></span>
        </div>

      <!-- Option 1: Yes, customers will sleep overnight -->

      <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
        <span><label>{{ __('Will customers sleep overnight somewhere as part of the activity?')}}</label>
               </span>
      </div>
      <div class="form-group sleeprequired" data-direction="">
          <input type="radio" name="sleep_required" value="yes" class="sleeprequired sleep_yes sleep_nomnremove transport_nomnremove">
          <label class="form-check-label">{{ __('Yes') }}</label>
      </div>

      <!-- Option 2: No, customers will not sleep overnight -->
      <div class="form-group sleeprequired" data-direction="">
          <input type="radio" name="sleep_required"  value="no" class="sleep_nomn transport_nomn">
          <label class="form-check-label">{{ __('No') }}</label>
      </div>



      <div class="specific-div d-none">
          <div class="form-group" data-direction="">
              <label>{{ __('Is accommodation included in the price?')}}</label>
              <br>
              <span></span>
          </div>
          <!-- Option 1: Yes, accommodation is included -->
          <div class="form-group option" data-direction="">
              <input type="radio"  value="yes" name="accommodation_included" class="accommodation_yes">
              <label class="form-check-label">{{ __('Yes') }}</label>
          </div>
          <!-- Option 2: No, accommodation is not included -->
          <div class="form-group option" data-direction="">
              <input type="radio"  value="no" name="accommodation_included" class="accommodation_no">
              <label class="form-check-label">{{ __('No') }}</label>
          </div>
      </div>



        {{-- <div class="fetch-entry"></div>
        <div class="form-group ml-4  add-entry">
          <button data-toggle="modal" data-target="#addEntryModal{{$language->code}}" onclick="resetItem('{{$language->code}}')"
                  class="btn btn-outline-secondary btn-sm text-success rounded border border-primary">
            {{__('Add Entry')}}</button>
        </div> --}}

      </div>
    </div>


    <div class="row">
      <div class="col">
          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
              <label>{{ __('Food & Drink') }}</label>
              <br>
              <span><b>{{ __('You can specify if food and drinks are included in your activity. If your activity is available with different menu options (example: 2-course meal or 3-course meal), or if your activity includes multiple meals (example: lunch and dinner), please list them as separate entries.?') }}</b>
                  <br></span>
                  <label>{{ __("Are food or drinks included in your activity?") }}</label>
          </div>


          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }} ml-4">
              <input type="radio"   value="yes"name="food_drink_required" onclick="" class="showFoodDrinkModal food_drink_yes"
                     class="form-check-input">
              <label class="form-check-label" for="food_drink_yes">Yes</label>
          </div>
          <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }} ml-4">
              <input type="radio" value="no" name="food_drink_required" class="food_drink_no"
                     class="form-check-input">
              <label class="form-check-label " for="food_drink_no">No</label>
          </div>
          <div class="fetch-entry"></div>
          {{-- <div class="form-group ml-4 add-entry">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
              Add Entry
            </button>
          </div> --}}
      </div>
  </div>
  <div class="p-4" id="output"></div>


  <div class="Inclusions">
    <div class="container-fluid">
        <div class="container">
            <h3 class="step-title mt-4">Inclusions & descriptions</h3>
            <p class="mb-0">This is the main information that customers will use on your activity details page to read, compare, and book an activity.</p>
            <ul class="ps-3">
                <li>
                    <p class="mb-0">Write all information below in <strong>English</strong> </p>
                </li>
                <li>
                    <p class="mb-0"> Avoid writing “we,” “our,” or mentioning your company’s name </p>
                </li>
            </ul>
            <ul class="ps-3">
                <li>
                    <p class="mb-0"><strong>Tic :</strong>Personalize the content for customers by saying “you”, “you’ll”, and action verbs, such as “explore”, “experience”, etc. </p>
                </li>
                <li>
                    <p class="mb-0"><strong>Tic :</strong>Avoid copying and pasting text from an existing website. We need to keep your content unique so we encourage more organic SEO traffic to your page.</p>
                </li>
            </ul>
            <h2 class="step-title">Inclusions & exclusions </h2>
            <h6>Gear and/or media inclusions</h6>
            <p class="mb-0">Name any equipment you provide that customers need for your activity, all included in your price from the customer perspective.</p>
            <div class="modal-card">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h6>Gear examples</h6>
                        <ul class="ps-3">
                            <li>
                                <p class="mb-0">Helmet </p>
                            </li>
                            <li>
                                <p class="mb-0"> Snorkeling gear </p>
                            </li>
                            <li>
                                <p class="mb-0"> Life vest </p>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-6">
                        <h6>Media examples</h6>
                    <ul class="ps-4">
                        <li>
                            <p class="mb-0">Map   </p>
                        </li>
                        <li>
                            <p class="mb-0"> Headset to hear the guide better </p>
                        </li>
                        <li>
                            <p class="mb-0">App</p>
                        </li>
                    </ul>
                    </div>
                </div>
            </div>
            <p class="mb-0">Is gear or media included in the price?</p>



            <h6>Main inclusions</h6>
            <p class="mb-0">Include all the main features that are included in the price. This allows customers to see the value for money for this activity.</p>
            <ul class="ps-3">
                <li>
                    <p class="mb-0"><strong>Tic :</strong> Stick to objective, tangible inclusions — avoid adjectives and subjective language </p>
                </li>
                <li>
                    <p class="mb-0"><strong>Tic :</strong> Keep your text short — no full sentences needed</p>
                </li>
            </ul>
            <div class="modal-card">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h6>Good inclusion examples</h6>
                        <ul class="ps-3">
                            <li>
                                <p class="mb-0">Hotel pickup and drop-off</p>
                            </li>
                            <li>
                                <p class="mb-0"> Headsets to hear the tour guide clearly</p>
                            </li>
                            <li>
                                <p class="mb-0">Entry tickets to Alhambra, Nasrid Palaces, and Generalife</p>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-6">
                    <h6>Inclusion examples to avoid</h6>
                    <ul class="ps-3">
                        <li>
                            <p class="mb-0">Most amazing and excellent tour ever </p>
                        </li>
                        <li>
                            <p class="mb-0">The professional, local guide will explain to you all there is to know. </p>
                        </li>
                        <li>
                            <p class="mb-0">Photo opportunities and an amazing time </p>
                        </li>
                    </ul>
                    </div>
                </div>
            </div>
            <div class="my-3">
                <textarea class="form-control border-danger" id=""  name="main_inclusions" style="height: 100px"></textarea>
                <label for="floatingTextarea2" class="text-danger"></label>
              </div>
            <h6>Exclusions (optional)</h6>
            <p class="mb-0">Name what customers need to pay extra for or what they may expect to see that isn’t included in the price</p>
            <ul class="ps-3">
                <li>
                    <p class="mb-0"><strong>Tic :</strong>Keep your text short. Write tangible exclusions, no sentences needed. </p>
                </li>
            </ul>

            <div class="modal-card">
                <div class="row">

                    <div class="col-12 col-md-6">
                    <h6>Inclusion examples to avoid</h6>
                    <ul class="ps-3">
                        <li>
                            <p class="mb-0">Hotel pickup and drop-off</p>
                        </li>
                        <li>
                            <p class="mb-0">Food and drinks</p>
                        </li>
                        <li>
                            <p class="mb-0">Gratuities</p>
                        </li>
                    </ul>
                    </div>
                    <div class="col-12 col-md-6">
                        <h6>Exclusion examples to avoid</h6>
                        <ul class="ps-3">
                            <li>
                                <p class="mb-0">Unfortunately, no food will be served on this tour but there is a café where you can purchase it.</p>
                            </li>
                            <li>
                                <p class="mb-0">Gratuities are not included, it is recommend to tip the guide 15-20% at the end of the tour.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="my-3">
                <textarea class="form-control border-danger text-danger" id=""  name="inclusion_optional_description"placeholder="" style="height: 100px"></textarea>
                <label for="floatingTextarea2" class="text-danger"></label>
              </div>
              <h2 class="step-title">Descriptions</h2>
            <h6>Short description</h6>
            <p class="mb-0">This is the customer’s first introduction to your activity. Aim to give customer a taste of what they’ll do in 2 or 3 sentences so they’ll want to learn more.</p>
            <ul class="ps-3">
                <li>
                    <p class="mb-0"><strong>Tic :</strong>Use action words such as “Explore...”, “Experience...”, or “Enjoy...” to get customers excited about what they’ll do </p>
                </li>
            </ul>
            <div class="my-3">
                <textarea class="form-control border-danger text-danger" id=""  name="inclusion_short_description" placeholder="" style="height: 100px"></textarea>
                <label for="floatingTextarea2" class="text-danger"></label>
              </div>
              <h6>Full description</h6>
              <p class="">This is the main description customers will see about your activity. Include a summary of the main activity in the first paragraph. Focus on your main unique selling points here.</p>
              <p class="mb-0">Then, write the itinerary and/or main activity features. Finally, let customers know how the activity ends (e.g. do they get dropped off at the hotel? Are they in a central location to explore more?)</p>
              <ul class="ps-3">
                  <li>
                      <p class="mb-0"><strong>Tic :</strong> Use action words such as “Explore...”, “Experience...”, or “Enjoy...” to get customers excited about what they’ll do </p>
                  </li>
                  <li>
                    <p class="mb-0"><strong>Tic :</strong> Write short paragraphs (300 characters or less) to make this section as scannable as possible</p>
                </li>
              </ul>
              <div class="my-3">
                <textarea class="form-control border-danger text-danger" id=""  name="          " placeholder="" style="height: 100px"></textarea>
                <label for="floatingTextarea2" class="text-danger">3000 characters left</label>
              </div>

              <h6>Activity highlights</h6>
              <p class="mb-0">Write 3-5 short phrases about what makes your activity special. Avoid repeating the itinerary. Ask yourself: what makes this activity stand out from others?</p>
              <ul class="ps-3">
                  <li>
                      <p class="mb-0"><strong>Tic :</strong> Start each highlight with an action word, such as “Discover...”, “Admire...”, or “Learn...” to let customers mentally visualize the experience</p>
                  </li>
              </ul>

              <div class="modal-card">
                <div class="row">

                    <div class="col-12 col-md-6">
                    <h6>Good highlight examples</h6>
                    <ul class="ps-3">
                        <li>
                            <p class="mb-0">Savor the bright flavors of Vietnamese food with an immersive cooking class</p>
                        </li>
                        <li>
                            <p class="mb-0">Be transported back to Ancient Rome as you stroll through the Colosseum</p>
                        </li>
                        <li>
                            <p class="mb-0">Discover 35,000 works of art up close and at your own pace </p>
                        </li>
                    </ul>
                    </div>
                    <div class="col-12 col-md-6">
                        <h6>Examples to avoid</h6>
                        <ul class="ps-3">
                            <li>
                                <p class="mb-0">Vatican Museums, Sistine Chapel, St. Peter’s Basilica.</p>
                            </li>
                            <li>
                                <p class="mb-0">Amazing views!</p>
                            </li>
                            <li>
                                <p class="mb-0">Entrance tickets, local guide, and lunch</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="my-3">
                <input type="text" name="inclusions_activity_one"  class="form-control border-danger">
                <div class="form-text text-danger">

                </div>
            </div>
            <div class="mb-3">
                <input type="text" name="inclusions_activity_two"  class="form-control border-danger">
                <div class="form-text text-danger">

                </div>
            </div>
            <div class="mb-3">
                <input type="text"  name="inclusions_activity_three" class="form-control border-danger">
                <div class="form-text text-danger">

                </div>
            </div>

            <!--<div class="mb-3">-->
            <!--    <div class="alert alert-danger alert-dismissible fade show" role="alert">-->
            <!--        Check the following fields and edit the flagged errors before saving and continuing: <a href="javascript">H Short description</a>-->
            <!--        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
            <!--      </div>-->
            <!--</div>-->
            <!--<div class="mb-3">-->
            <!--    <div class="alert alert-danger alert-dismissible fade show" role="alert">-->
            <!--        Check the following fields and edit the flagged errors before saving and continuing: <a href="javascript">H Short description</a>-->
            <!--        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
            <!--      </div>-->
            <!--</div>-->
            <!--<div class="mb-3">-->
            <!--    <div class="alert alert-danger alert-dismissible fade show" role="alert">-->
            <!--        Check the following fields and edit the flagged errors before saving and continuing: <a href="javascript">H Short description</a>-->
            <!--        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
            <!--      </div>-->
            <!--</div>-->
        </div>
    </div>
</div>



                          {{-- mn model end--}}





                          {{-- model mn --}}
                          <div class="modal fade"   id="addEntryModalfooddring" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                <div class="modal-header border-0 px-3">
                                  <h1 class="modal-title fs-5" id="exampleModalLabel"> Add entry </h1>
                                </div>
                                <div class="modal-body p-4 px-3">
                                  <form id="foodDrinksForm" method="post">

                                  <div class="food-drinks-entry__food">
                                    <h2 class="food-drinks-entry__title text-white"> Food </h2>
                                    <div class="gyg-radio-group gyg-radio-group--vertical">
                                      <div class="gyg-radio gyg-radio-vertical">
                                        <div class="gyg-radio-container">
                                          <input id="food-no" type="radio" name="food_required" id="redio1" class="food_required_no gyg-radio-input"
                                                 value="no">
                                          <label for="food-no" class="gyg-radio-label"> No </label>

                                        </div>



                                        <div class="gyg-radio gyg-radio-vertical">
                                          <div class="gyg-radio-container">
                                            <input id="food-yes" type="radio" name="food_required" class="food_required_yes gyg-radio-input" value="yes"
                                                   id="redio2">
                                            <label for="food-yes" class="gyg-radio-label"> Yes </label>

                                          </div>
                                          <div class="accordion collapseTwo border-start-3 my-4" id="collapseTwo" style="overflow: auto;
                                                                                                                         height: 240px;
                                                                                                        border-left: 4px solid #808080;
                                                                                                                   overflow-x: hidden;
                                                                                                                     padding-left: 23px;">
                                            <div class="accordion-body">
                                              <div class="mb-2">
                                                <label for="meal-type" class="form-label">Type of meal</label>
                                                <select id="meal-type" class="form-select form-control" name="Type_of_meal" aria-label="Default select example">
                                                  <option value="null" selected>select Type</option>
                                                  <option value="Full meal">Full meal</option>
                                                  <option value="Food tasting">Food tasting</option>
                                                  <option value="Cooking class">Cooking class</option>
                                                  <option value="Buffet">Buffet</option>
                                                  <option value="Cooking class">Cooking class</option>
                                                  <option value="Snack">Snack</option>
                                                  <option value="Picnic">Picnic</option>
                                                  <option value="Packed meal">Packed meal</option>
                                                  <option value="BBQ">BBQ</option>
                                                </select>
                                              </div>
                                              <div class="courses py-1 px-5" style="display: none;" >
                                                <label for="exampleDataList" class="form-label">Number of dishes/courses</label>
                                                <select class="form-select form-control" name="Type_of_meal" aria-label="Default select example">
                                                  <option value="null" selected>select Type</option>
                                                  <option value="1">1</option>
                                                  <option value="2">2</option>
                                                  <option value="3">3</option>
                                                  <option value="4">4</option>
                                                  <option value="5">5</option>
                                                  <option value="6">6</option>
                                                  <option value="7">7</option>
                                                  <option value="8">8</option>
                                                  <option value="9">9</option>
                                                  <option value="10">10</option>
                                                </select>
                                              </div>
                                              <div class="courses_optional py-1 px-5" style="display: none;">
                                                <label for="exampleDataList" class="form-label">Number of dishes/courses: (Optional)</label>
                                                <select class="form-select form-control" name="Type_of_meal" aria-label="Default select example">
                                                  <option value="null" selected>select Type</option>
                                                  <option value="1">1</option>
                                                  <option value="2">2</option>
                                                  <option value="3">3</option>
                                                  <option value="4">4</option>
                                                  <option value="5">5</option>
                                                  <option value="6">6</option>
                                                  <option value="7">7</option>
                                                  <option value="8">8</option>
                                                  <option value="9">9</option>
                                                  <option value="10">10</option>
                                                </select>
                                              </div>
                                              <div class="all_eat mb-3 py-1 px-5" style="display: none;">
                                                <label for="exampleDataList" class="form-label">All you can eat</label><br>
                                                <input id="drinks-no" type="radio" name="eat-option" class="gyg-radio-input" value="no">
                                                <label for="drinks-no" class="gyg-radio-label"> No </label>
                                                <input id="drinks-yes" type="radio" name="eat-option" class="gyg-radio-input" value="yes">
                                                <label for="drinks-yes" class="gyg-radio-label"> Yes </label>
                                              </div>

                                              <div class="mb-2">

                                                <label for="exampleDataList" class="form-label">Time of
                                                  day</label>
                                                <select class="form-select form-control" name="Time_of_day" aria-label="Default select example">
                                                  <option value="select Type" selected>select Type</option>
                                                  <option value="Breakfast">Breakfast</option>
                                                  <option value="Lunch">Lunch</option>
                                                  <option value="Dinner">Dinner</option>
                                                  <option value="Brunch">Brunch</option>
                                                </select>
                                              </div>
                                              <div class="mb-1">
                                                <label for="exampleDataList" class="form-label">Type of tag</label>
                                                <select class="multiple-tags form-select form-control" name="Type_of_tag[]" aria-label="Default select example" multiple="multiple">
                                                    <option value="Avocado">Avocado</option>
                                                    <option value="Bacon">Bacon</option>
                                                    <option value="Bagel">Bagel</option>
                                                    <option value="Baguette">Baguette</option>
                                                    <option value="Beans">Beans</option>
                                                    <option value="Beef">Beef</option>
                                                    <option value="Biscuits">Biscuits</option>
                                                    <option value="Bread">Bread</option>
                                                    <option value="Brownie">Brownie</option>
                                                    <option value="Bruschetta">Bruschetta</option>
                                                    <option value="Bulgur">Bulgur</option>
                                                    <option value="Burger">Burger</option>
                                                    <option value="Burrito">Burrito</option>
                                                    <option value="Cake">Cake</option>
                                                    <option value="Casserole">Casserole</option>
                                                    <option value="Caviar">Caviar</option>
                                                    <option value="Ceviche">Ceviche</option>
                                                    <option value="Charcuterie">Charcuterie</option>
                                                    <option value="Cheese">Cheese</option>
                                                    <option value="Chicken">Chicken</option>
                                                    <option value="Chickpeas">Chickpeas</option>
                                                    <option value="Chocolate">Chocolate</option>
                                                    <option value="Churros">Churros</option>
                                                    <option value="Cold cuts">Cold cuts</option>
                                                    <option value="Cookies">Cookies</option>
                                                    <option value="Couscous">Couscous</option>
                                                    <option value="Crêpe">Crêpe</option>
                                                    <option value="Criossant">Criossant</option>
                                                    <option value="Cupcake">Cupcake</option>
                                                    <option value="Cured meat">Cured meat</option>
                                                    <option value="Curry">Curry</option>
                                                    <option value="Custard">Custard</option>
                                                    <option value="Dates">Dates</option>
                                                    <option value="Dim sum">Dim sum</option>
                                                    <option value="Dip">Dip</option>
                                                    <option value="Donut">Donut</option>
                                                    <option value="Dried fruits">Dried fruits</option>
                                                    <option value="Duck">Duck</option>
                                                    <option value="Dumplings">Dumplings</option>
                                                    <option value="Egg">Egg</option>
                                                    <option value="Empanadas">Empanadas</option>
                                                    <option value="Escalope">Escalope</option>
                                                    <option value="Fish">Fish</option>
                                                    <option value="Fondue">Fondue</option>
                                                    <option value="Fries">Fries</option>
                                                    <option value="Fruits">Fruits</option>
                                                    <option value="Gelato">Gelato</option>
                                                    <option value="Gingerbread">Gingerbread</option>
                                                    <option value="Gnocchi">Gnocchi</option>
                                                    <option value="Granola">Granola</option>
                                                    <option value="Granola bar">Granola bar</option>
                                                    <option value="Guacamole">Guacamole</option>
                                                    <option value="Ham">Ham</option>
                                                    <option value="Honey">Honey</option>
                                                    <option value="Hot dog">Hot dog</option>
                                                    <option value="Hummus">Hummus</option>
                                                    <option value="Ice cream">Ice cream</option>
                                                    <option value="Lamb">Lamb</option>
                                                    <option value="Lasagne">Lasagne</option>
                                                    <option value="Lentils">Lentils</option>
                                                    <option value="Lobster">Lobster</option>
                                                    <option value="Macarons">Macarons</option>
                                                    <option value="Marmelade">Marmelade</option>
                                                    <option value="Marshmellows">Marshmellows</option>
                                                    <option value="Meatballs">Meatballs</option>
                                                    <option value="Mezes">Mezes</option>
                                                    <option value="Muffin">Muffin</option>
                                                    <option value="Mustard">Mustard</option>
                                                    <option value="Nachos">Nachos</option>
                                                    <option value="Noodles">Noodles</option>
                                                    <option value="Nuts">Nuts</option>
                                                    <option value="Oatmeal">Oatmeal</option>
                                                    <option value="Olive oil">Olive oil</option>
                                                    <option value="Olives">Olives</option>
                                                    <option value="Omelette">Omelette</option>
                                                    <option value="Oysters">Oysters</option>
                                                    <option value="Paella">Paella</option>
                                                    <option value="Pancake">Pancake</option>
                                                    <option value="Pasta">Pasta</option>
                                                    <option value="Pastry">Pastry</option>
                                                    <option value="Pie">Pie</option>
                                                    <option value="Pintxos">Pintxos</option>
                                                    <option value="Pizza">Pizza</option>
                                                    <option value="Plantains">Plantains</option>
                                                    <option value="Popcorn">Popcorn</option>
                                                    <option value="Pork">Pork</option>
                                                    <option value="Potatoes">Potatoes</option>
                                                    <option value="Pretzel">Pretzel</option>
                                                    <option value="Quesadilla">Quesadilla</option>
                                                    <option value="Quinoa">Quinoa</option>
                                                    <option value="Rabbit">Rabbit</option>
                                                    <option value="Ramen">Ramen</option>
                                                    <option value="Rice">Rice</option>
                                                    <option value="Risotto">Risotto</option>
                                                    <option value="Salad">Salad</option>
                                                    <option value="Salami">Salami</option>
                                                    <option value="Sandwich">Sandwich</option>
                                                    <option value="Sausage">Sausage</option>
                                                    <option value="Scone">Scone</option>
                                                    <option value="Seafood">Seafood</option>
                                                    <option value="Soup">Soup</option>
                                                    <option value="Steak">Steak</option>
                                                    <option value="Stew">Stew</option>
                                                    <option value="Sushi">Sushi</option>
                                                    <option value="Sweets">Sweets</option>
                                                    <option value="Tacos">Tacos</option>
                                                    <option value="Tapas">Tapas</option>
                                                    <option value="Tiramisu">Tiramisu</option>
                                                    <option value="Tofu">Tofu</option>
                                                    <option value="Tortilla">Tortilla</option>
                                                    <option value="Truffle">Truffle</option>
                                                    <option value="Turkey">Turkey</option>
                                                    <option value="Tzatziki">Tzatziki</option>
                                                    <option value="Veal">Veal</option>
                                                    <option value="Vegetables">Vegetables</option>
                                                    <option value="Venison">Venison</option>
                                                    <option value="Vinegar">Vinegar</option>
                                                    <option value="Waffle">Waffle</option>
                                                    <option value="Wrap">Wrap</option>
                                                    <option value="Yoghurt">Yoghurt</option>

                                                </select>
                                              </div>
                                              <div class="mb-3">
                                                <h6 class="text-white" >Which dietary restrictions can you accommodate?</h6>
                                                <p>Please select all that are relevant</p>
                                                <div class="row ps-3">

                                                  <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                      <input class="form-check-input" type="checkbox" name="Vegetarian" value="Diabetic" id="check1" >
                                                      <label class="form-check-label" for="check1">
                                                        Diabetic
                                                      </label>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                      <input class="form-check-input" type="checkbox"  name="Vegetarian"  value="Egg_free" id="check2">
                                                      <label class="form-check-label" for="check2">
                                                        Egg-free
                                                      </label>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                      <input class="form-check-input" type="checkbox" name="Vegetarian" value="Gluten_free" id="check3">
                                                      <label class="form-check-label" for="check3">
                                                        Gluten-free
                                                      </label>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                      <input class="form-check-input" type="checkbox"  name="Vegetarian" value="Halal" id="check4">
                                                      <label class="form-check-label" for="check4">
                                                        Halal
                                                      </label>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                      <input class="form-check-input" type="checkbox"  name="Vegetarian" value="Keto" id="check5">
                                                      <label class="form-check-label" for="check5">
                                                        Keto
                                                      </label>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                      <input class="form-check-input" type="checkbox"  name="Vegetarian" value="Kosher" id="check6">
                                                      <label class="form-check-label" for="check6">
                                                        Kosher
                                                      </label>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                      <input class="form-check-input" type="checkbox"  name="Vegetarian" value=" Lactose_free" id="check7">
                                                      <label class="form-check-label" for="check7">
                                                        Lactose-free
                                                      </label>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                      <input class="form-check-input" type="checkbox"  name="Vegetarian" value="Low_carb" id="check8">
                                                      <label class="form-check-label" for="check8">
                                                        Low-carb
                                                      </label>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                      <input class="form-check-input" type="checkbox"  name="Vegetarian" value="Seafood_fish_free" id="check9">
                                                      <label class="form-check-label" for="check9">
                                                        Seafood/fish-free
                                                      </label>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                      <input class="form-check-input" type="checkbox"  name="Vegetarian" value="Pescatarian" id="check10">
                                                      <label class="form-check-label" for="check10">
                                                        Pescatarian
                                                      </label>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                      <input class="form-check-input" type="checkbox"  name="Vegetarian" value="Vegan" id="check11">
                                                      <label class="form-check-label" for="check11">
                                                        Vegan
                                                      </label>
                                                    </div>
                                                  </div>
                                                  <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                      <input class="form-check-input" type="checkbox"  name="Vegetarian" value="Vegetarian" id="check12">
                                                      <label class="form-check-label" for="check12">
                                                        Vegetarian
                                                      </label>
                                                    </div>
                                                  </div>
                                                </div>

                                              </div>
                                            </div>
                                          </div>


                                        </div><!---->
                                      </div>
                                      <div class="food-drinks-entry__drinks mt-3">
                                        <h2 class="food-drinks-entry__title text-white"> Drinks </h2>
                                        <div class="gyg-radio-group gyg-radio-group--vertical">
                                          <div class="gyg-radio gyg-radio-vertical">
                                            <div class="gyg-radio-container"><input id="drinks-no" type="radio" name="drink_required"
                                                                                    class="drink_required_no gyg-radio-input"  value="no">
                                              <label for="drinks-no" class="gyg-radio-label"> No </label>
                                            </div>
                                            <div>
                                              <div class=" collapsed border-0">
                                                <div class="gyg-radio gyg-radio-vertical">
                                                  <div class="gyg-radio-container"><input id="drinks-yes" type="radio" name="drink_required"
                                                                                          class="drink_required_yes gyg-radio-input" value="yes"> <label for="drinks-yes" class=" gyg-radio-label"> Yes
                                                    </label>
                                                  </div>
                                                  <div id="collapseThree" class="p-3
                                                  my-4" style="overflow: auto;border-left: 4px solid #808080;">
                                                    <div class="mb-3">

                                                      <label for="exampleDataList" class="form-label">Tag</label>
                                                      <select class="multiple-tags form-select form-control" name="Type_of_drinks[]" aria-label="Default select example" multiple="multiple">
                                                          <option value="1 beer">1 beer</option>
                                                          <option value="2 alcoholic drinks">2 alcoholic drinks</option>
                                                          <option value="2 beer">2 beer</option>
                                                          <option value="2 cocktails">2 cocktails</option>
                                                          <option value="2 cups of mulled wine">2 cups of mulled wine</option>
                                                          <option value="2 glasses of cava">2 glasses of cava</option>
                                                          <option value="2 glasses of champagne">2 glasses of champagne</option>
                                                          <option value="2 glasses of mimosa">2 glasses of mimosa</option>
                                                          <option value="2 glasses of port wine">2 glasses of port wine</option>
                                                          <option value="2 glasses of Prosecco">2 glasses of Prosecco</option>
                                                          <option value="2 glasses of punch">2 glasses of punch</option>
                                                          <option value="2 glasses of sangria">2 glasses of sangria</option>
                                                          <option value="2 glasses of wine">2 glasses of wine</option>
                                                          <option value="2 non-alcoholic drinks">2 non-alcoholic drinks</option>
                                                          <option value="3 alcoholic drinks">3 alcoholic drinks</option>
                                                          <option value="3 beer">3 beer</option>
                                                          <option value="3 cocktails">3 cocktails</option>
                                                          <option value="3 cups of mulled wine">3 cups of mulled wine</option>
                                                          <option value="3 glasses of cava">3 glasses of cava</option>
                                                          <option value="3 glasses of champagne">3 glasses of champagne</option>
                                                          <option value="3 glasses of mimosa">3 glasses of mimosa</option>
                                                          <option value="3 glasses of port wine">3 glasses of port wine</option>
                                                          <option value="3 glasses of Prosecco">3 glasses of Prosecco</option>
                                                          <option value="3 glasses of punch">3 glasses of punch</option>
                                                          <option value="3 glasses of sangria">3 glasses of sangria</option>
                                                          <option value="3 glasses of wine">3 glasses of wine</option>
                                                          <option value="3 non-alcoholic drinks">3 non-alcoholic drinks</option>
                                                          <option value="Alcoholic drink">Alcoholic drink</option>
                                                          <option value="Alcoholic drinks">Alcoholic drinks</option>
                                                          <option value="Aperitif">Aperitif</option>
                                                          <option value="Beer">Beer</option>
                                                          <option value="Beer tasting">Beer tasting</option>
                                                          <option value="Bottle of cava">Bottle of cava</option>
                                                          <option value="Bottle of champagne">Bottle of champagne</option>
                                                          <option value="Bottle of local liquor">Bottle of local liquor</option>
                                                          <option value="Bottle of Prosecco">Bottle of Prosecco</option>
                                                          <option value="Bottle of wine">Bottle of wine</option>
                                                          <option value="Cava">Cava</option>
                                                          <option value="Champagne">Champagne</option>
                                                          <option value="Cider">Cider</option>
                                                          <option value="Cocktail">Cocktail</option>
                                                          <option value="Cocktails">Cocktails</option>
                                                          <option value="Coconut water">Coconut water</option>
                                                          <option value="Coffee">Coffee</option>
                                                          <option value="Cup of mulled wine">Cup of mulled wine</option>
                                                          <option value="Drink tasting">Drink tasting</option>
                                                          <option value="Energy drink">Energy drink</option>
                                                          <option value="Gin">Gin</option>
                                                          <option value="Glass of cava">Glass of cava</option>
                                                          <option value="Glass of champagne">Glass of champagne</option>
                                                          <option value="Glass of mimosa">Glass of mimosa</option>
                                                          <option value="Glass of port wine">Glass of port wine</option>
                                                          <option value="Glass of Prosecco">Glass of Prosecco</option>
                                                          <option value="Glass of punch">Glass of punch</option>
                                                          <option value="Glass of sangria">Glass of sangria</option>
                                                          <option value="Glass of wine">Glass of wine</option>
                                                          <option value="Half bottle of champagne">Half bottle of champagne</option>
                                                          <option value="Half bottle of wine">Half bottle of wine</option>
                                                          <option value="Hot chocolate">Hot chocolate</option>
                                                          <option value="Hot drink">Hot drink</option>
                                                          <option value="Hot drinks">Hot drinks</option>
                                                          <option value="Hot juice">Hot juice</option>
                                                          <option value="Iced tea">Iced tea</option>
                                                          <option value="Juice">Juice</option>
                                                          <option value="Juices">Juices</option>
                                                          <option value="Lemonade">Lemonade</option>
                                                          <option value="Liquor">Liquor</option>
                                                          <option value="Liquor tasting">Liquor tasting</option>
                                                          <option value="Local liquor">Local liquor</option>
                                                          <option value="Long drink">Long drink</option>
                                                          <option value="Long drinks">Long drinks</option>
                                                          <option value="Milk">Milk</option>
                                                          <option value="Mimosas">Mimosas</option>
                                                          <option value="Mocktail">Mocktail</option>
                                                          <option value="Mocktails">Mocktails</option>
                                                          <option value="Mulled wine">Mulled wine</option>
                                                          <option value="Non-alcoholic drink">Non-alcoholic drink</option>
                                                          <option value="Non-alcoholic drinks">Non-alcoholic drinks</option>
                                                          <option value="Port wine">Port wine</option>
                                                          <option value="Prosecco">Prosecco</option>
                                                          <option value="Punch">Punch</option>
                                                          <option value="Rum">Rum</option>
                                                          <option value="Sangria">Sangria</option>
                                                          <option value="Shot of alcohol">Shot of alcohol</option>
                                                          <option value="Shots of alcohol">Shots of alcohol</option>
                                                          <option value="Smoothie">Smoothie</option>
                                                          <option value="Smoothies">Smoothies</option>
                                                          <option value="Soft drink">Soft drink</option>
                                                          <option value="Soft drinks">Soft drinks</option>
                                                          <option value="Sports drink">Sports drink</option>
                                                          <option value="Tea">Tea</option>
                                                          <option value="Tequila">Tequila</option>
                                                          <option value="Unlimited alcoholic drinks">Unlimited alcoholic drinks</option>
                                                          <option value="Unlimited beer">Unlimited beer</option>
                                                          <option value="Unlimited champagne">Unlimited champagne</option>
                                                          <option value="Unlimited cocktails">Unlimited cocktails</option>
                                                          <option value="Unlimited mimosas">Unlimited mimosas</option>
                                                          <option value="Unlimited non-alcoholic drinks">Unlimited non-alcoholic drinks</option>
                                                          <option value="Unlimited soft drinks">Unlimited soft drinks</option>
                                                          <option value="Unlimited water">Unlimited water</option>
                                                          <option value="Unlimited wine">Unlimited wine</option>
                                                          <option value="Vodka">Vodka</option>
                                                          <option value="Water">Water</option>
                                                          <option value="Whiskey">Whiskey</option>
                                                          <option value="Whiskey tasting">Whiskey tasting</option>
                                                          <option value="Whisky">Whisky</option>
                                                          <option value="Whisky tasting">Whisky tasting</option>
                                                          <option value="Wine">Wine</option>
                                                          <option value="Wine tasting">Wine tasting</option>
                                                        </select>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="d-flex justify-content-end">
                                            {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                                            <button type="button" id="submitBtn" class="btn btn-info rounded ms-2">Save Entry</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  </form>
                                </div>

                              </div>
                            </div>
                          </div>




{{-- model mn --}}

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Refund Policy') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_refund_policy" rows="5"
                                  placeholder="Enter Refund Policy"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Event Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords"
                                  placeholder="Enter Meta Keywords" data-role="tagsinput">
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Event Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="Enter Meta Description"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              @php $currLang = $language; @endphp

                              @foreach ($languages as $language)
                                @continue($language->id == $currLang->id)

                                <div class="form-check py-0">
                                  <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox"
                                      onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                    <span class="form-check-sign">{{ __('Clone for') }} <strong
                                        class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                      {{ __('language') }}</span>
                                  </label>
                                </div>
                              @endforeach
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>

                <div id="sliders"></div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="EventSubmitadmin" class="btn btn-success">
                {{ __('Save') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  @php
    $languages = App\Models\Language::get();
  @endphp
  <script>
    let languages = "{{ $languages }}";
  </script>
  <script type="text/javascript" src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
  <script src="{{ asset('assets/admin/js/admin_dropzone.js') }}"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function () {
      $('.multiple-tags').select2();

      $("#meal-type").change(function () {
        $(".courses, .courses_optional, .all_eat").hide();
        var selectedMeal = $(this).val();
        if (selectedMeal === "Full meal") {
          $(".courses").show();
        } else if (selectedMeal === "Food tasting") {
          $(".courses_optional, .all_eat").show();
        } else if (selectedMeal === "Cooking class") {
          $(".courses_optional").show();
        } else if (selectedMeal === "Buffet" || selectedMeal === "BBQ") {
          $(".all_eat").show();
        }
      });

      $("#submitBtn").click(function () {
        var timeOfDaySelect = document.querySelector('select[name="Time_of_day"]');
        let selectedFood = $('#meal-type').val();
        var selectElement = document.getElementsByName("Type_of_tag[]")[0];
        var selectElementDrink = document.getElementsByName("Type_of_drinks[]")[0];
        var selectedDrinks = Array.from(selectElementDrink.selectedOptions).map(option => option.value);
        var selectedOptions = Array.from(selectElement.selectedOptions).map(option => option.value);
        var supportedDiet = Array.from(document.querySelectorAll('input[name="Vegetarian"]:checked')).map(checkbox => checkbox.value);
        console.log(supportedDiet);
        console.log(selectedOptions);
        console.log(selectedFood);
        console.log(selectedDrinks);
        console.log(timeOfDaySelect);
        var outputMessage = `
      <div style="border:solid;" class="p-2">
        <h4> ${selectedFood}  + Drink</h4>
          ${timeOfDaySelect.value}<br>
         <b>Food: </b> ${selectedOptions}<br>
     <b>Diet Supported:</b>  ${supportedDiet.join(', ')}<br>
       <b>Selected Drinks:</b>  ${selectedDrinks.join(', ')}
</div>

`;

        $('#output').html(outputMessage);

        $('#addEntryModalfooddring').modal('hide');

      });

      $('#foodDrinksForm').submit(function (event) {
        event.preventDefault(); // Prevent the default form submission
        console.log('Form submitted!');
      });
    });
  </script>

@endsection

@section('variables')
  <script>
    "use strict";
    var storeUrl = "{{ route('admin.event.imagesstore') }}";
    var removeUrl = "{{ route('admin.event.imagermv') }}";
    var loadImgs = 0;
  // google map api code
  let map, marker, geocoder;

function initMap() {
  // Initialize map
  map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: 37.7749, lng: -122.4194 },
    zoom: 12
  });

  // Initialize marker
  marker = new google.maps.Marker({
    position: { lat: 37.7749, lng: -122.4194 },
    map: map,
    draggable: true
  });

  // Initialize geocoder
  geocoder = new google.maps.Geocoder();

  // Add event listener for marker drag
  marker.addListener('dragend', onMarkerDragEnd);
}

function onMarkerDragEnd(event) {
  const position = event.latLng;

  // Update marker position
  marker.setPosition(position);

  // Get address details based on marker position
  geocoder.geocode({ location: position }, (results, status) => {
    if (status === 'OK') {
      if (results[0]) {
        const addressComponents = results[0].address_components;
        let country, city, state, zip;

        for (const component of addressComponents) {
          if (component.types.includes('locality')) {
            city = component.long_name;
          }
          if (component.types.includes('administrative_area_level_1')) {
            state = component.short_name;
          }
          if (component.types.includes('postal_code')) {
            zip = component.long_name;
          }
         if (component.types.includes('country')) {
          country = component.long_name;
        }
        }

        const formattedAddress = results[0].formatted_address;
        const latitude = position.lat();
        const longitude = position.lng();

        document.getElementById('location').value = formattedAddress;
        document.getElementById('city').value = city;
        document.getElementById('country').value = country;
        document.getElementById('state').value = state;
        document.getElementById('zip').value = zip;
        document.getElementById('lat').value = latitude;
        document.getElementById('lng').value = longitude;
      }
    } else {
      console.error('Geocoder failed due to: ' + status);
    }
  });
}
  // google api code end
  // $(document).ready(function(){
  //       $('.rtl').eq(0).children().closest('input').attr("placeholder", "أدخل اسم الحدث");
  //       $('.rtl').eq(0).children().closest('label').text("عنوان الحدث*");
  //       $('.rtl').eq(1).children().closest('select').find('option').eq(0).text("اختر الفئة");
  //       $('.rtl').eq(1).children().closest('label').text("فئة*");
  //       $('.rtl').eq(2).children().closest('input').attr("placeholder", "عنوان");
  //       $('.rtl').eq(2).children().closest('label').text("عنوان*");
  //       $('.rtl').eq(3).children().closest('select').find('option').remove();
  //       $('.rtl').eq(3).children().closest('label').text("مقاطعة*");
  //       $('.rtl').eq(4).children().closest('label').text("أشبع*");
  //       $('.rtl').eq(4).children().closest('input').attr("placeholder", "إدخال الدولة");
  //       $('.rtl').eq(5).children().closest('label').text("مدينة*");
  //       $('.rtl').eq(5).children().closest('input').attr("placeholder", "أدخل المدينة");
  //       $('.rtl').eq(6).children().closest('label').text("الرمز البريدي / الرمز البريدي*");
  //       $('.rtl').eq(6).children().closest('input').attr("placeholder", "أدخل الرمز البريدي");
  //       $('.rtl').eq(7).children().closest('label').text("وصف*");
  //       $('.rtl').eq(7).children().closest('textarea').attr("placeholder", "أدخل وصف الحدث");

  //       $('.rtl').eq(8).children().closest('label').text("سياسة الاسترجاع*");
  //       $('.rtl').eq(8).children().closest('textarea').attr("placeholder", "أدخل سياسة الاسترداد");
  //       $('.rtl').eq(9).children().closest('label').text("الكلمات المفتاحية للحدث*");
  //       $('.rtl').eq(9).children().children().closest('input').attr("placeholder", "أدخل الكلمات الأساسية ميتا");
  //       $('.rtl').eq(10).children().closest('label').text("وصف تعريف الحدث*");
  //       $('.rtl').eq(10).children().closest('textarea').attr("placeholder", "أدخل وصف التعريف");
  //       $('.rtl').eq(3).children().closest('select').append('<option>حدد الدولة</option>\
  //       <option>اختر الدولة</option>\
  //   <option value="أفغانستان">أفغانستان</option>\
  //   <option value="جزر آلاند">جزر آلاند</option>\
  //   <option value="ألبانيا">ألبانيا</option>\
  //   <option value="الجزائر">الجزائر</option>\
  //   <option value="ساموا الأمريكية">ساموا الأمريكية</option>\
  //   <option value="أندورا">أندورا</option>\
  //   <option value="أنغولا">أنغولا</option>\
  //   <option value="أنغويلا">أنغويلا</option>\
  //   <option value="القطب الجنوبي">القطب الجنوبي</option>\
  //   <option value="أنتيغوا وبربودا">أنتيغوا وبربودا</option>\
  //   <option value="الأرجنتين">الأرجنتين</option>\
  //   <option value="أرمينيا">أرمينيا</option>\
  //   <option value="أروبا">أروبا</option>\
  //   <option value="أستراليا">أستراليا</option>\
  //   <option value="النمسا">النمسا</option>\
  //   <option value="أذربيجان">أذربيجان</option>\
  //   <option value="البهاما">البهاما</option>\
  //   <option value="البحرين">البحرين</option>\
  //   <option value="بنغلاديش">بنغلاديش</option>\
  //   <option value="بربادوس">بربادوس</option>\
  //   <option value="بيلاروسيا">بيلاروسيا</option>\
  //   <option value="بلجيكا">بلجيكا</option>\
  //   <option value="بليز">بليز</option>\
  //   <option value="بنين">بنين</option>\
  //   <option value="برمودا">برمودا</option>\
  //   <option value="بوتان">بوتان</option>\
  //   <option value="بوليفيا">بوليفيا</option>\
  //   <option value="بونير وسانت أوستايوس وسابا">بونير وسانت أوستايوس وسابا</option>\
  //   <option value="البوسنة والهرسك">البوسنة والهرسك</option>\
  //   <option value="بوتسوانا">بوتسوانا</option>\
  //   <option value="جزيرة بوفيه">جزيرة بوفيه</option>\
  //   <option value="البرازيل">البرازيل</option>\
  //   <option value="إقليم المحيط البريطاني الهندي">إقليم المحيط البريطاني الهندي</option>\
  //   <option value="بروناي">بروناي</option>\
  //   <option value="بلغاريا">بلغاريا</option>\
  //   <option value="بوركينا فاسو">بوركينا فاسو</option>\
  //   <option value="بوروندي">بوروندي</option>\
  //   <option value="كمبوديا">كمبوديا</option>\
  //   <option value="الكاميرون">الكاميرون</option>\
  //   <option value="كندا">كندا</option>\
  //   <option value="الرأس الأخضر">الرأس الأخضر</option>\
  //   <option value="جزر كايمان">جزر كايمان</option>\
  //   <option value="جمهورية أفريقيا الوسطى">جمهورية أفريقيا الوسطى</option>\
  //   <option value="تشاد">تشاد</option>\
  //   <option value="شيلي">شيلي</option>\
  //   <option value="الصين">الصين</option>\
  //   <option value="جزيرة الكريسماس">جزيرة الكريسماس</option>\
  //   <option value="جزر كوكوس (كيلينغ)">جزر كوكوس (كيلينغ)</option>\
  //   <option value="كولومبيا">كولومبيا</option>\
  //   <option value="جزر القمر">جزر القمر</option>\
  //   <option value="الكونغو">الكونغو</option>\
  //   <option value="جمهورية الكونغو الديمقراطية">جمهورية الكونغو الديمقراطية</option>\
  //   <option value="جزر كوك">جزر كوك</option>\
  //   <option value="كوستاريكا">كوستاريكا</option>\
  //   <option value="كوت ديفوار">كوت ديفوار</option>\
  //   <option value="كرواتيا">كرواتيا</option>\
  //   <option value="كوبا">كوبا</option>\
  //   <option value="كوراكاو">كوراكاو</option>\
  //   <option value="قبرص">قبرص</option>\
  //   <option value="جمهورية التشيك">جمهورية التشيك</option>\
  //   <option value="الدنمارك">الدنمارك</option>\
  //   <option value="جيبوتي">جيبوتي</option>\
  //   <option value="دومينيكا">دومينيكا</option>\
  //   <option value="جمهورية الدومينيكان">جمهورية الدومينيكان</option>\
  //   <option value="الإكوادور">الإكوادور</option>\
  //   <option value="مصر">مصر</option>\
  //   <option value="السلفادور">السلفادور</option>\
  //   <option value="غينيا الاستوائية">غينيا الاستوائية</option>\
  //   <option value="إريتريا">إريتريا</option>\
  //   <option value="استونيا">استونيا</option>\
  //   <option value="إثيوبيا">إثيوبيا</option>\
  //   <option value="جزر فوكلاند (مالفيناس)">جزر فوكلاند (مالفيناس)</option>\
  //   <option value="جزر فارو">جزر فارو</option>\
  //   <option value="فيجي">فيجي</option>\
  //   <option value="فنلندا">فنلندا</option>\
  //   <option value="فرنسا">فرنسا</option>\
  //   <option value="غويانا الفرنسية">غويانا الفرنسية</option>\
  //   <option value="بولينيزيا الفرنسية">بولينيزيا الفرنسية</option>\
  //   <option value="المناطق الجنوبية لفرنسا">المناطق الجنوبية لفرنسا</option>\
  //   <option value="الجابون">الجابون</option>\
  //   <option value="غامبيا">غامبيا</option>\
  //   <option value="جورجيا">جورجيا</option>\
  //   <option value="ألمانيا">ألمانيا</option>\
  //   <option value="غانا">غانا</option>\
  //   <option value="جبل طارق">جبل طارق</option>\
  //   <option value="اليونان">اليونان</option>\
  //   <option value="غرينلاند">غرينلاند</option>\
  //   <option value="غرينادا">غرينادا</option>\
  //   <option value="جوادلوب">جوادلوب</option>\
  //   <option value="غوام">غوام</option>\
  //   <option value="غواتيمالا">غواتيمالا</option>\
  //   <option value="غيرنزي">غيرنزي</option>\
  //   <option value="غينيا">غينيا</option>\
  //   <option value="غينيا بيساو">غينيا بيساو</option>\
  //   <option value="غيانا">غيانا</option>\
  //   <option value="هايتي">هايتي</option>\
  //   <option value="جزيرة هيرد وجزر ماكدونالد">جزيرة هيرد وجزر ماكدونالد</option>\
  //   <option value="الكرسي الرسولي (الفاتيكان)">الكرسي الرسولي (الفاتيكان)</option>\
  //   <option value="هندوراس">هندوراس</option>\
  //   <option value="هونغ كونغ">هونغ كونغ</option>\
  //   <option value="هنغاريا">هنغاريا</option>\
  //   <option value="أيسلندا">أيسلندا</option>\
  //   <option value="الهند">الهند</option>\
  //   <option value="إندونيسيا">إندونيسيا</option>\
  //   <option value="إيران (جمهورية إيران الإسلامية)">إيران (جمهورية إيران الإسلامية)</option>\
  //   <option value="العراق">العراق</option>\
  //   <option value="أيرلندا">أيرلندا</option>\
  //   <option value="جزيرة آيل أوف مان">جزيرة آيل أوف مان</option>\
  //   <option value="إسرائيل">إسرائيل</option>\
  //   <option value="إيطاليا">إيطاليا</option>\
  //   <option value="جامايكا">جامايكا</option>\
  //   <option value="اليابان">اليابان</option>\
  //   <option value="جيرسي">جيرسي</option>\
  //   <option value="الأردن">الأردن</option>\
  //   <option value="كازاخستان">كازاخستان</option>\
  //   <option value="كينيا">كينيا</option>\
  //   <option value="كيريباس">كيريباس</option>\
  //   <option value="كوريا (جمهورية كوريا الشعبية الديمقراطية)">كوريا (جمهورية كوريا الشعبية الديمقراطية)</option>\
  //   <option value="كوريا (جمهورية كوريا)">كوريا (جمهورية كوريا)</option>\
  //   <option value="كوسوفو">كوسوفو</option>\
  //   <option value="الكويت">الكويت</option>\
  //   <option value="قيرغيزستان">قيرغيزستان</option>\
  //   <option value="جمهورية لاو الديمقراطية الشعبية">جمهورية لاو الديمقراطية الشعبية</option>\
  //   <option value="لاتفيا">لاتفيا</option>\
  //   <option value="لبنان">لبنان</option>\
  //   <option value="ليسوتو">ليسوتو</option>\
  //   <option value="ليبيريا">ليبيريا</option>\
  //   <option value="الجماهيرية الليبية">الجماهيرية الليبية</option>\
  //   <option value="ليختنشتاين">ليختنشتاين</option>\
  //   <option value="ليتوانيا">ليتوانيا</option>\
  //   <option value="لوكسمبورغ">لوكسمبورغ</option>\
  //   <option value="ماكاو">ماكاو</option>\
  //   <option value="مقدونيا (جمهورية مقدونيا السابقة)">مقدونيا (جمهورية مقدونيا السابقة)</option>\
  //   <option value="مدغشقر">مدغشقر</option>\
  //   <option value="ملاوي">ملاوي</option>\
  //   <option value="ماليزيا">ماليزيا</option>\
  //   <option value="جزر المالديف">جزر المالديف</option>\
  //   <option value="مالي">مالي</option>\
  //   <option value="مالطا">مالطا</option>\
  //   <option value="جزر مارشال">جزر مارشال</option>\
  //   <option value="مارتينيك">مارتينيك</option>\
  //   <option value="موريتانيا">موريتانيا</option>\
  //   <option value="موريشيوس">موريشيوس</option>\
  //   <option value="مايوت">مايوت</option>\
  //   <option value="المكسيك">المكسيك</option>\
  //   <option value="ميكرونيزيا (ولايات ميكرونيزيا الموحدة)">ميكرونيزيا (ولايات ميكرونيزيا الموحدة)</option>\
  //   <option value="مولدوفا (جمهورية مولدوفا)">مولدوفا (جمهورية مولدوفا)</option>\
  //   <option value="موناكو">موناكو</option>\
  //   <option value="منغوليا">منغوليا</option>\
  //   <option value="الجبل الأسود">الجبل الأسود</option>\
  //   <option value="مونتسيرات">مونتسيرات</option>\
  //   <option value="المغرب">المغرب</option>\
  //   <option value="موزمبيق">موزمبيق</option>\
  //   <option value="ميانمار">ميانمار</option>\
  //   <option value="ناميبيا">ناميبيا</option>\
  //   <option value="ناورو">ناورو</option>\
  //   <option value="نيبال">نيبال</option>\
  //   <option value="هولندا">هولندا</option>\
  //   <option value="جزر الأنتيل الهولندية">جزر الأنتيل الهولندية</option>\
  //   <option value="كاليدونيا الجديدة">كاليدونيا الجديدة</option>\
  //   <option value="نيوزيلندا">نيوزيلندا</option>\
  //   <option value="نيكاراغوا">نيكاراغوا</option>\
  //   <option value="النيجر">النيجر</option>\
  //   <option value="نيجيريا">نيجيريا</option>\
  //   <option value="نيوي">نيوي</option>\
  //   <option value="جزيرة نورفولك">جزيرة نورفولك</option>\
  //   <option value="جزر مريانا الشمالية">جزر مريانا الشمالية</option>\
  //   <option value="النرويج">النرويج</option>\
  //   <option value="عمان">عمان</option>\
  //   <option value="باكستان">باكستان</option>\
  //   <option value="بالاو">بالاو</option>\
  //   <option value="فلسطين">فلسطين</option>\
  //   <option value="بنما">بنما</option>\
  //   <option value="بابوا غينيا الجديدة">بابوا غينيا الجديدة</option>\
  //   <option value="باراغواي">باراغواي</option>\
  //   <option value="بيرو">بيرو</option>\
  //   <option value="الفلبين">الفلبين</option>\
  //   <option value="جزر بيتكيرن">جزر بيتكيرن</option>\
  //   <option value="بولندا">بولندا</option>\
  //   <option value="البرتغال">البرتغال</option>\
  //   <option value="بورتوريكو">بورتوريكو</option>\
  //   <option value="قطر">قطر</option>\
  //   <option value="ريونيون">ريونيون</option>\
  //   <option value="رومانيا">رومانيا</option>\
  //   <option value="روسيا (الاتحاد الروسي)">روسيا (الاتحاد الروسي)</option>\
  //   <option value="رواندا">رواندا</option>\
  //   <option value="سانت بارتيليمي">سانت بارتيليمي</option>\
  //   <option value="سانت هيلانة وأسينشن وتريستان دا كونها">سانت هيلانة وأسينشن وتريستان دا كونها</option>\
  //   <option value="سانت كيتس ونيفيس">سانت كيتس ونيفيس</option>\
  //   <option value="سانت لوسيا">سانت لوسيا</option>\
  //   <option value="سانت مارتين (الجزء الهولندي)">سانت مارتين (الجزء الهولندي)</option>\
  //   <option value="سانت بيير وميكلون">سانت بيير وميكلون</option>\
  //   <option value="سانت فينسنت وجزر غرينادين">سانت فينسنت وجزر غرينادين</option>\
  //   <option value="ساموا">ساموا</option>\
  //   <option value="سان مارينو">سان مارينو</option>\
  //   <option value="ساو تومي وبرينسيبي">ساو تومي وبرينسيبي</option>\
  //   <option value="المملكة العربية السعودية">المملكة العربية السعودية</option>\
  //   <option value="السنغال">السنغال</option>\
  //   <option value="صربيا">صربيا</option>\
  //   <option value="سيشيل">سيشيل</option>\
  //   <option value="سيراليون">سيراليون</option>\
  //   <option value="سنغافورة">سنغافورة</option>\
  //   <option value="سينت مارتن">سينت مارتن</option>\
  //   <option value="سلوفاكيا">سلوفاكيا</option>\
  //   <option value="سلوفينيا">سلوفينيا</option>\
  //   <option value="جزر سليمان">جزر سليمان</option>\
  //   <option value="الصومال">الصومال</option>\
  //   <option value="جنوب أفريقيا">جنوب أفريقيا</option>\
  //   <option value="جورجيا الجنوبية وجزر ساندويتش الجنوبية">جورجيا الجنوبية وجزر ساندويتش الجنوبية</option>\
  //   <option value="جنوب السودان">جنوب السودان</option>\
  //   <option value="إسبانيا">إسبانيا</option>\
  //   <option value="سريلانكا">سريلانكا</option>\
  //   <option value="السودان">السودان</option>\
  //   <option value="سورينام">سورينام</option>\
  //   <option value="سفالبارد وجان ماين">سفالبارد وجان ماين</option>\
  //   <option value="سوازيلاند">سوازيلاند</option>\
  //   <option value="السويد">السويد</option>\
  //   <option value="سويسرا">سويسرا</option>\
  //   <option value="الجمهورية العربية السورية">الجمهورية العربية السورية</option>\
  //   <option value="تايوان (مقاطعة الصين)">تايوان (مقاطعة الصين)</option>\
  //   <option value="طاجيكستان">طاجيكستان</option>\
  //   <option value="تنزانيا">تنزانيا</option>\
  //   <option value="تايلاند">تايلاند</option>\
  //   <option value="تيمور الشرقية">تيمور الشرقية</option>\
  //   <option value="توغو">توغو</option>\
  //   <option value="توكيلو">توكيلو</option>\
  //   <option value="تونغا">تونغا</option>\
  //   <option value="ترينيداد وتوباغو">ترينيداد وتوباغو</option>\
  //   <option value="تونس">تونس</option>\
  //   <option value="تركيا">تركيا</option>\
  //   <option value="تركمانستان">تركمانستان</option>\
  //   <option value="جزر توركس وكايكوس">جزر توركس وكايكوس</option>\
  //   <option value="توفالو">توفالو</option>\
  //   <option value="أوغندا">أوغندا</option>\
  //   <option value="أوكرانيا">أوكرانيا</option>\
  //   <option value="الإمارات العربية المتحدة">الإمارات العربية المتحدة</option>\
  //   <option value="المملكة المتحدة">المملكة المتحدة</option>\
  //   <option value="الولايات المتحدة الأمريكية">الولايات المتحدة الأمريكية</option>\
  //   <option value="الولايات المتحدة الصغرى البعيدة الصغيرة">الولايات المتحدة الصغرى البعيدة الصغيرة</option>\
  //   <option value="أوروغواي">أوروغواي</option>\
  //   <option value="أوزبكستان">أوزبكستان</option>\
  //   <option value="فانواتو">فانواتو</option>\
  //   <option value="فنزويلا (جمهورية فنزويلا البوليفارية)">فنزويلا (جمهورية فنزويلا البوليفارية)</option>\
  //   <option value="فيتنام">فيتنام</option>\
  //   <option value="جزر الفرجين البريطانية">جزر الفرجين البريطانية</option>\
  //   <option value="جزر الفرجين الأمريكية">جزر الفرجين الأمريكية</option>\
  //   <option value="واليس وفوتونا">واليس وفوتونا</option>\
  //   <option value="الصحراء الغربية">الصحراء الغربية</option>\
  //   <option value="اليمن">اليمن</option>\
  //   <option value="زامبيا">زامبيا</option>\
  //   <option value="زيمبابوي">زيمبابوي</option>');
  //     });

  if ($('#collapse22').hasClass('show')) {

$(".transport_yes:eq(1)").change(function () {

  alert('clicked');
  if ($(this).is(":checked")) {
    $(".add-entry:eq(1)").removeClass('d-none');
  }
})
$(".transport_no:eq(1)").change(function () {

  if ($(this).is(":checked")) {

    $(".add-entry:eq(1)").addClass('d-none');
    $(".fetch-entry:eq(1)").html("");
  }
})
}
else{
$(".transport_yes:eq(0)").change(function () {

  if ($(this).is(":checked")) {
    $(".add-entry:eq(0)").removeClass('d-none');
  }
})
$(".transport_no:eq(0)").change(function () {

  if ($(this).is(":checked")) {

    $(".add-entry:eq(0)").addClass('d-none');
    $(".fetch-entry:eq(0)").html("");
  }
})

}

var i = 0;

var transports_en = [];
var type_of_shares_en = [];
var transports_ar = [];
var type_of_shares_ar = [];
function getEntry(lang, j = null) {
i = j != null ? j : i;
var transport_select = $("select[name='" + lang +"_transport_select']").find(':selected').text();
var transport_val = $("select[name='" + lang +"_transport_select']").val();
var type_val = $("input[name='" + lang +"_type_of_share']:checked").val();

var type_of_share = $("input[name='" + lang +"_type_of_share']:checked").parent().text().trim();
if(lang == 'en'){
  transports_en[i] =transport_val;
  type_of_shares_en[i] =type_val;
            }
else{
  transports_ar[i] =transport_val;
  type_of_shares_ar[i] =type_val;
                }
var html = `
<div class='row ml-4 remove_item${i}'>
<div class='col-lg-6'>
  <div class='card'>


      <div class='card-body' data-val='${type_val}'>

          <input type="hidden" name="${lang}_transport" value="${lang == 'en' ?  transports_en : transports_ar}">
<input type="hidden" name="${lang}_type_of_transport" value="${lang == 'en' ?  type_of_shares_en : type_of_shares_ar}">
          <span class='card-icons float-right'>
              <i class='fas fa-edit edit-icon cursor-pointer' onclick="editItem('${lang}', '${i}')"></i>
<i class='fas fa-trash-alt remove-icon cursor-pointer' onclick="removeItem('${lang}', '${i}')"></i>

          </span>
<p class='font-weight-bold' data-val='${transport_val}'>${transport_select}</p>
          ${type_of_share}
      </div>
  </div>
</div>
</div>`;

if (j === null) {

  if ($('#collapse22').hasClass('show')) {

    $(".fetch-entry:eq(1)").append(html); // Append to the second element with class "fetch-entry"
  } else {
    $(".fetch-entry:eq(0)").append(html); // Append to the first element with class "fetch-entry"
  }
} else {

  $(".remove_item" + j).replaceWith(html)
}

$("#addEntryModal"+lang).modal('hide')
i++;
}

function removeItem(lang, i) {

$(".remove_item" + i).remove();
}

function editItem(lang, i) {
var transport_select = $(".remove_item" + i).find('.font-weight-bold').attr('data-val');
var type_of_share = $(".remove_item" + i).find('.card-body').attr('data-val');

$("select[name='" + lang +"_transport_select']").val(transport_select)
// $("input[name='type_of_share']").val(type_of_share)
$("input[name='" + lang + "_type_of_share']").filter("[value='" + type_of_share + "']").prop("checked", true);


$("#addEntryModal" + lang).modal('show');
$("#addEntryModal" + lang).find('.btn-primary').text("Edit").attr('onclick', 'getEntry("' + lang + '",' + i + ')');

}

function resetItem(code) {
$("select[name='" + code +"_transport_select']").prop("selectedIndex", 0);
// $("input[name='type_of_share']").val(type_of_share)
$("input[name='" + code +"_type_of_share']").prop("checked", false);
$("#addEntryModal" + code).find('.btn-primary').text("Add").attr('onclick', `getEntry('${code}')`);

}

function setDiv(){


if (!$('#collapse22').hasClass('show')) {

  $(".transport_yes:eq(1)").change(function () {


    if ($(this).is(":checked")) {
      $(".add-entry:eq(1)").removeClass('d-none');
    }
  })
  $(".transport_no:eq(1)").change(function () {

    if ($(this).is(":checked")) {

      $(".add-entry:eq(1)").addClass('d-none');
      $(".fetch-entry:eq(1)").html("");
    }
  })
}
else{
  $(".transport_yes:eq(0)").change(function () {

    if ($(this).is(":checked")) {
      $(".add-entry:eq(0)").removeClass('d-none');
    }
  })
  $(".transport_no:eq(0)").change(function () {

    if ($(this).is(":checked")) {

      $(".add-entry:eq(0)").addClass('d-none');
      $(".fetch-entry:eq(0)").html("");
    }
  })

}
}


$(document).ready(function () {

$('.transport_nomn').click(function () {
  // Toggle the class to show/hide the specific div
  $('.specific-div').addClass('d-none');
});


$('.transport_nomnremove').click(function () {
  // Toggle the class to show/hide the specific div
  $('.specific-div').removeClass('d-none');
});

});


$(document).ready(function() {
$(".sleeprequired input[type='radio']").change(function() {
  if ($(this).is(":checked")) {
    var selectedValue = $(this).val(); // Get the value of the selected radio button
  }
});
});
$(document).ready(function() {
$('.showFoodDrinkModal').click(function () {
$('#addEntryModalfooddring').modal('show');
// You can perform additional operations or data manipulation here
});

});
$(document).ready(function() {
$("#redio2").on("click", function () {
alert("c");
$(".collapseTwo").addClass("show")

});
});


$(document).ready(function() {
$("#submitButton").click(function() {
var checkedValues = [];

$(".form-check-input").each(function() {
if (this.checked) {
  checkedValues.push($(this).val());
}
});

if (checkedValues.length > 0) {
alert("Checked values: " + checkedValues.join(", "));
} else {
alert("No checkboxes are checked.");
}
});
});


$(document).ready(function() {
$("#collapseTwo").addClass("d-none");
$(".food_required_no").click(function() {
$("#collapseTwo").addClass("d-none");
});
$(".food_required_yes").click(function() {
$("#collapseTwo").removeClass("d-none");
});



$("#collapseThree").addClass("d-none");
$(".drink_required_no").click(function() {
$("#collapseThree").addClass("d-none");
});
$(".drink_required_yes").click(function() {
$("#collapseThree").removeClass("d-none");
});

});



</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDB8ahpPhHkuWIh0JyxSg3uALTj9ynOS74&callback=initMap"></script>

@endsection

