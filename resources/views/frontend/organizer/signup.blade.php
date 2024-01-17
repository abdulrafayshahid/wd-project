@extends('frontend.layout')
@section('pageHeading')
@if (!empty($pageHeading))
{{ $pageHeading->organizer_signup_page_title ?? __('Signup') }}
@else
{{ __('Signup') }}
@endif
@endsection
@php
$metaKeywords = !empty($seo->meta_keyword_organizer_signup) ? $seo->meta_keyword_organizer_signup : '';
$metaDescription = !empty($seo->meta_description_organizer_signup) ? $seo->meta_description_organizer_signup : '';
@endphp
@section('meta-keywords', "{{ $metaKeywords }}")
@section('meta-description', "$metaDescription")

@section('hero-section')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<!-- Page Banner Start -->
<section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy" data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
        <div class="banner-inner">
            <h2 class="page-title">
                @if (!empty($pageHeading))
                {{ $pageHeading->organizer_signup_page_title ?? __('Signup') }}
                @else
                {{ __('Signup') }}
                @endif
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item active">
                        @if (!empty($pageHeading))
                        {{ $pageHeading->organizer_signup_page_title ?? __('Signup') }}
                        @else
                        {{ __('Signup') }}
                        @endif
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</section>
<!-- Page Banner End -->
@endsection
@section('content')
<!-- SignUp Area Start -->
<div class="signup-area pt-115 rpt-95 pb-120 rpb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form id="login-form" name="login_form" class="login-form" action="{{ route('organizer.create') }}" method="POST">
                    @csrf
                    <div class="form-group mt-3" style="margin-bottom: 30px !important">
                    <h2>SIGN UP</h2>
                        <p>{{ __('Already have an account') }}? <a class="text-info" href="{{ route('organizer.login') }}">{{ __('Login Now') }}</a></p>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="company_or_individual">{{ __('Company Or Individual') }} *</label>
                                <div class="form-group">
                                    <input type="radio" name="company_or_individual" class="company_or_individual" value="company" required>
                                    <span>Company</span>
                                    <input type="radio" name="company_or_individual" class="company_or_individual" value="individual" required>
                                    <span>Individual</span>
                                </div>
                                @error('company_or_individual')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-md-6 register_type">
                                    <div class="form-group">
                                        <label for="registration_type">{{ __('Commercial No') }} *</label>
                                        <div class="form-group">
                                            <input type="text" name="commercial_no" class="form-control" placeholder="Commercial No">
                                        </div>
                                        @error('commercial_no')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 register_type">
                                    <div class="form-group">
                                        <label for="registration_type">{{ __('Vat No') }} *</label>
                                        <div class="form-group">
                                            <input type="text" name="vat_no" class="form-control" placeholder="Vat No">
                                        </div>
                                        @error('vat_no')
                                        <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="fname"> {{ __('Name') }} *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="{{ __('Enter Your Full Name') }}">
                                @error('name')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="lname">{{ __('Username') }} *</label>
                                <input type="text" name="username" value="{{ old('username') }}" id="username" class="form-control" placeholder="{{ __('Enter Your Username') }}">
                                @error('username')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">{{ __('Email Address') }} *</label>
                                <input type="email" name="email" value="{{ old('email') }}" id="email" class="form-control" placeholder="{{ __('Enter Your Email Address') }}">
                                @error('email')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="phone">{{ __('Phone No') }} *</label>
                                <input type="number" name="phone" value="{{ old('phone') }}" id="phone" class="form-control" placeholder="{{ __('Enter Your Phone No') }}" required>
                                @error('phone')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password">{{ __('Password') }} *</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Enter Password') }}">
                                @error('password')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="re-password">{{ __('Re-enter Password') }} *</label>
                                <input type="password" name="password_confirmation" id="re-password" class="form-control" placeholder="{{ __('Re-enter Password') }}">
                                @error('password_confirmation')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        @if ($basicInfo->google_recaptcha_status == 1)
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                                @error('g-recaptcha-response')
                                <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        @endif
                        <div class="col-sm-8">
                            <?php 
                                $site_setting = \App\Models\BasicSettings\Basic::first();
                                ?>
                            <div class="form-group">
                                <div class="d-flex align-items-baseline">
                                    <input type="checkbox" name="termsandcondition" class="termsandconditioncheckbox">
                                    <p class="ml-2 m-0">Accept All Terms and Conditions? <button type="button" class="text-info termsandcondition">Terms & Consitions</button></p>
                                </div>
                                @error('termsandcondition')
                                <p class="text-danger">Please accept Tems And Conditions Before Signup</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <button class="theme-btn rf-theme-btn br-30" type="submit" data-loading-text="Please wait...">{{ __('Signup') }}</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content rounded-0">
        <div class="modal-body p-4 px-5">

          
          <div class="main-content text-center">
              
              <a href="#" class="close-btn" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true"><span class="icon-close2"></span></span>
                </a>

              <div class="warp-icon mb-4">
                <span class="icon-lock2"></span>
              </div>
              <form action="#">
              <label for="" style="text-transform: capitalize;">{{ $site_setting->terms_and_condition }}</label>
                <div class="d-flex  mt-5">
                  <div class="mx-auto">
                  <button type="button" class="btn btn-primary featured-ok">Ok</button>
                  </div>
                </div>
              </form>
            
          </div>

        </div>
      </div>
    </div>
  </div>
<!-- SignUp Area End -->
<script>
    $(document).ready(function() {
        $('.termsandcondition').on('click', function () {
    $('#exampleModalCenter').modal('show');
});
$('.featured-ok').on('click', function () {
    $('#exampleModalCenter').modal('hide');
});
        $('.register_type').hide();
        $('.company_or_individual').on("click", function() {
            var a = $(this).val();
            if (a == "company") {
                $('.register_type').show();
                $('.register_type').setAttribute("required");
            } else {
                $('.register_type').hide();

            }
        });
    });
</script>
@endsection