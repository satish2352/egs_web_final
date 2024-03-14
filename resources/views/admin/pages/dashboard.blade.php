@extends('admin.layout.master')
@section('content')
    <style>
        @import url("https://fonts.google.com/specimen/Titillium+Web");
        .card {
            background-color: #fff;
            border-radius: 10px;
            border: none;
            position: relative;
            margin-bottom: 30px;
            box-shadow: 0 0.46875rem 2.1875rem rgba(90, 97, 105, 0.1), 0 0.9375rem 1.40625rem rgba(90, 97, 105, 0.1), 0 0.25rem 0.53125rem rgba(90, 97, 105, 0.12), 0 0.125rem 0.1875rem rgba(90, 97, 105, 0.1);
        }

        .l-bg-cherry {
            background: #d24d4d73 !important;
            color: #fff;
        }

        .l-bg-blue-dark {
            background: #a2d9c6 !important;
            color: #fff;
        }

        .l-bg-green-dark {
            background: #9b9b9b8f !important;
            color: #fff;
        }

        .l-bg-orange-dark {
            background: #e2bfda !important;
            color: #fff;
        }

        .card .card-statistic-3 .card-icon-large .fas,
        .card .card-statistic-3 .card-icon-large .far,
        .card .card-statistic-3 .card-icon-large .fab,
        .card .card-statistic-3 .card-icon-large .fal {
            font-size: 110px;
        }

        .card .card-statistic-3 .card-icon {
            text-align: center;
            line-height: 50px;
            margin-left: 15px;
            color: #000;
            position: absolute;
            right: -5px;
            top: 20px;
            opacity: 0.1;
        }

        .l-bg-cyan {
            background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
            color: #fff;
        }

        .l-bg-green {
            background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
            color: #fff;
        }

        .l-bg-orange {
            background: linear-gradient(to right, #f9900e, #ffba56) !important;
            color: #fff;
        }

        .l-bg-cyan {
            background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
            color: #fff;
        }
        .homeIcon1{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card1{
            background: darksalmon;
            border-radius: 10px;
        }
        .homeIcon2{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card2{
            background: #c9bcff;
            border-radius: 10px;
        }
        .homeIcon3{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card3{
            background: #ec8ca3;
            border-radius: 10px;
        }
        .homeIcon4{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card4{
            background: #d2ec8c;
            border-radius: 10px;
        }
        .homeIcon5{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card5{
            background: #8cecbf;
            border-radius: 10px;
        }
        .homeIcon6{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card6{
            background: #ec8cdb;
            border-radius: 10px;
        }
        .homeIcon7{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card7{
            background: #8cecdb;
            border-radius: 10px;
        }
        .homeIcon8{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card8{
            background: #8cec93;
            border-radius: 10px;
        }
        .homeIcon9{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card9{
            background: #e0dfeb;
            border-radius: 10px;
        }
        .homeIcon10{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card10{
            background: #85edc5;
            border-radius: 10px;
        }
        .homeIcon11{
            font-size:2.5rem;
            color:#ec671f;
        }
        .dashboard_Card11{
            background: #add8f6;
            border-radius: 10px;
        }
        .media-body{
            text-align: right !important;
            font-size: 24px;
            font-family: "Anta", sans-serif;
            /* font-family: titillium -webkit-body; */
        }
        .media-body h3{
            text-align: right !important;
            font-size: 24px;
            font-family: "Anta", sans-serif;
            /* font-family: titillium -webkit-body; */
        }
    </style>
<?php //$data_for_url = session('data_for_url'); ?>
    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Dashboard
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
              {{-- @if (isset($status) && $return_data['status'] == 'success')
                <div class="alert alert-success" role="alert">
                    {{ $return_data['msg'] }}
                </div>
                @endif --}}
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <div class="container">
                                <div class="col-md-12">
                                    <div class="row ">

                                        <div class="grey-bg container-fluid">
                                            <section id="minimal-statistics">
                                                <div class="row justify-content-center">
                                                    <div class="col-xl-4 col-sm-6 col-12">
                                                        <div class="card">
                                                            <div class="card-content dashboard_Card2">
                                                                <div class="card-body">
                                                                    <div class="media d-flex">
                                                                        <div class="align-self-center icon_color">
                                                                            <i class="homeIcon2 fas fa-newspaper primary font-large-2 float-left"></i>
                                                                        </div>
                                                                        <div class="media-body text-right">
                                                                            <h3>11</h3>
                                                                            {{-- <h3>{{$return_data['coursesOffered']}}</h3> --}}
                                                                            <span>Users</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="col-xl-4 col-sm-6 col-12">
                                                        <div class="card">
                                                            <div class="card-content dashboard_Card3">
                                                                <div class="card-body">
                                                                    <div class="media d-flex">
                                                                        <div class="align-self-center icon_color">
                                                                            <i class="homeIcon3 fas fa-address-card primary font-large-2 float-left"></i>
                                                                        </div>
                                                                        <div class="media-body text-right">
                                                                            <h3>11</h3>
                                                                            {{-- <h3>{{$return_data['gallary']}}</h3> --}}
                                                                            <span>Projects</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-sm-6 col-12">
                                                        <div class="card">
                                                            <div class="card-content dashboard_Card4">
                                                                <div class="card-body">
                                                                    <div class="media d-flex">
                                                                        <div class="align-self-center icon_color">
                                                                            <i class="homeIcon4 fa fa-bars primary font-large-2 float-left"></i>
                                                                        </div>
                                                                        <div class="media-body text-right">
                                                                            <h3>11</h3>
                                                                            {{-- <h3>{{$return_data['galleryCategory']}}</h3> --}}
                                                                            <span>Labours</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-sm-6 col-12">
                                                        <div class="card">
                                                            <div class="card-content dashboard_Card5">
                                                                <div class="card-body">
                                                                    <div class="media d-flex">
                                                                        <div class="align-self-center icon_color">
                                                                            <i class="homeIcon5 fa fa-bars primary font-large-2 float-left"></i>
                                                                        </div>
                                                                        <div class="media-body text-right">
                                                                            <h3>11</h3>
                                                                            {{-- <h3>{{$return_data['galleryMain']}}</h3> --}}
                                                                            <span>District </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-sm-6 col-12">
                                                        <div class="card">
                                                            <div class="card-content dashboard_Card6">
                                                                <div class="card-body">
                                                                    <div class="media d-flex">
                                                                        <div class="align-self-center icon_color">
                                                                            <i class="homeIcon6 fas fa-newspaper primary font-large-2 float-left"></i>
                                                                        </div>
                                                                        <div class="media-body text-right">
                                                                            <h3>11</h3>
                                                                            {{-- <h3>{{$return_data['upcomingCourses']}}</h3> --}}
                                                                            <span>Taluka</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-sm-6 col-12">
                                                        <div class="card">
                                                            <div class="card-content dashboard_Card7">
                                                                <div class="card-body">
                                                                    <div class="media d-flex">
                                                                        <div class="align-self-center icon_color">
                                                                            <i class="homeIcon7 fas fa-address-card primary font-large-2 float-left"></i>
                                                                        </div>
                                                                        <div class="media-body text-right">
                                                                            <h3>11</h3>
                                                                            {{-- <h3>{{$return_data['testimonial']}}</h3> --}}
                                                                            <span>Village</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-4 col-sm-6 col-12">
                                                        <div class="card">
                                                            <div class="card-content dashboard_Card8">
                                                                <div class="card-body">
                                                                    <div class="media d-flex">
                                                                        <div class="align-self-center icon_color">
                                                                            <i class="homeIcon8 fa fa-bars primary font-large-2 float-left"></i>
                                                                        </div>
                                                                        <div class="media-body text-right">
                                                                            <h3>11</h3>
                                                                            {{-- <h3>{{$return_data['applicationForm']}}</h3> --}}
                                                                            <span>Skills</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
