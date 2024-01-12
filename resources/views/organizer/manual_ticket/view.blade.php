@extends('organizer.layout')

@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        .fl-left {
            float: left
        }

        .fl-right {
            float: right
        }



        .row {
            overflow: hidden
        }

        .card-2 {
            display: table-row;
            width: 49%;
            background-color: #1a2035;
            color: #989898;

            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
            border-radius: 4px;
            position: relative
        }

        .card-2+.card-2 {
            margin-left: 2%
        }

        .date {
            display: table-cell;
            width: 25%;
            position: relative;
            text-align: center;
            border-right: 2px dashed #dadde6
        }

        .date:before,
        .date:after {
            content: "";
            display: block;
            width: 30px;
            height: 30px;
            background-color: #DADDE6;
            position: absolute;
            top: -15px;
            right: -15px;
            z-index: 1;
            border-radius: 50%
        }

        .date:after {
            top: auto;
            bottom: -15px
        }

        .date time {
            display: block;
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%)
        }

        .date time span {
            display: block
        }

        .date time span:first-child {
            color: #2b2b2b;
            font-weight: 600;
            font-size: 250%
        }

        .date time span:last-child {
            text-transform: uppercase;
            font-weight: 600;
            margin-top: -10px
        }

        .card-cont {
            display: table-cell;
            width: 75%;
            font-size: 85%;
            padding: 10px 10px 10px 50px
        }

        .card-cont h3 {
            color: #3C3C3C;
            font-size: 130%
        }

        .row:last-child .card:last-of-type .card-cont h3 {
            text-decoration: line-through
        }

        .card-cont>div {
            display: table-row
        }

        .card-cont .even-date i,
        .card-cont .even-info i,
        .card-cont .even-date time,
        .card-cont .even-info p {
            display: table-cell
        }

        .card-cont .even-date i,
        .card-cont .even-info i {
            padding: 5% 5% 0 0
        }

        .card-cont .even-info p {
            padding: 30px 50px 0 0
        }

        .card-cont .even-date time span {
            display: block
        }

        .card-cont a {
            display: block;
            text-decoration: none;
            width: 80px;
            height: 30px;
            background-color: #D8DDE0;
            color: #fff;
            text-align: center;
            line-height: 30px;
            border-radius: 2px;
            position: absolute;
            right: 10px;
            bottom: 10px
        }

        .row:last-child .card:first-child .card-cont a {
            background-color: #037FDD
        }

        .row:last-child .card:last-child .card-cont a {
            background-color: #F8504C
        }



        .card+.card {
            margin-left: 0
        }

        .card-cont .even-date,
        .card-cont .even-info {
            font-size: 75%
        }
    </style>
    <div class="page-header">
        <h4 class="page-title">{{ __('Tickets View ') }}</h4>
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
                <a href="#">{{ __('Ticket View') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Ticket View') }}</a>
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
                                {{ __('Ticket View') }}
                            </div>
                        </div>

                        <div class="col-lg-8 mt-2 mt-lg-0">
                            <button class="btn btn-danger float-lg-right float-none btn-sm ml-2 mt-1 bulk-delete d-none"
                                data-href="{{ route('organizer.manual.ticket.bulk_delete') }}"><i
                                    class="flaticon-interface-5"></i>
                                Delete</button>
                            <button id="btn" class="float-lg-right float-none btn btn-primary">Print</button>
                        </div>
                    </div>
                </div>

                <div class="card-body" id="card-body">


                    <div class="row">
                        <div class="col-lg-12" style="display: flex;justify-content: center;">
                            <article class="card-2 fl-left ">
                                <section class="date">
                                    <time datetime="23th feb">
                                        @php
                                            $carbonDate = Carbon\Carbon::createFromFormat('Y-m-d', $events->start_date);
                                            $formattedDate = $carbonDate->format('d');
                                            $formattedDate2 = $carbonDate->format('M');
                                        @endphp
                                        <span class="text-white">{{ $formattedDate }}</span><span>{{ $formattedDate2 }}</span>
                                    </time>
                                </section>
                                <section class="card-cont">
                                    <small>{{ $ticket->customer_name }}</small>
                                    <small>{{ $ticket->customer_email }}</small>
                                    <h3 class="text-white pt-3">{{ $events->title }}</h3>
                                    <div class="even-date" style="display: flex">
                                        <i class="fa fa-calendar"></i>
                                        <time>
                                            <span>Event Start Date</span>
                                            <span>{{ $events->start_date }}</span>
                                        </time>
                                        <i class="fa fa-calendar"></i>
                                        <time>
                                            <span>Event End Date</span>
                                            <span>{{ $events->end_date }}</span>
                                        </time>
                                    </div>
                                    <div class="even-info" style="display: flex; align-items:center">
                                        <i class="fa fa-map-marker"></i>
                                        <p>{{ $events->address }}</p>
                                    </div>
                                </section>
                            </article>
                        </div>

                    </div>

                </div>

                <div class="card-footer">

                </div>
            </div>
        </div>
    </div>
    <script>
        $("#btn").click(function() {
            printDiv();

        });

        function printDiv() {

            var divToPrint = document.getElementById('card-body');

            var newWin = window.open('', 'Print-Window');

            newWin.document.open();

            newWin.document.write(
                '<html><body style="display:flex;justify-content: space-around;margin-top:2rem;" onload="window.print()">' +
                divToPrint.innerHTML + '</body></html>');

            newWin.document.close();

            setTimeout(function() {
                newWin.close();
            }, 10);

        }
    </script>
@endsection
