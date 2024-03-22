@extends('admin.layout.master')

@section('content')

<!-- CSS -->
<style>
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        /* background-color: rgb(0, 0, 0); */
        /* background-color: rgba(0, 0, 0, 0.9); */
        background-color: rgb(0 0 0 / 25%);
    }

    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }

    .close {
        position: absolute;
        /* top: 15px;
        right: 35px; */
        top: 18%;
        right: 50px;
        /* color: #f1f1f1; */
        color: #0d0c0c;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        /* color: #bbb; */
        color: #0d0c0c;
        text-decoration: none;
        cursor: pointer;
    }

    .download_btn{
        display: flex;
        justify-content: right;
        top: 20px;
    }
</style>
    <div class="main-panel">
        <div class="content-wrapper mt-7">

            <div class="row justify-content-center">
                <div class="col-10 grid-margin ">

                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8 d-flex justify-content-start align-items-center">
                            <h3 class="page-title">
                                Labour Details
                            </h3>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 d-flex justify-content-end align-items-center">
                            <div>
                                <a href="{{ route('list-labours') }}" class="btn btn-sm btn-primary ml-3">Back</a>
                            </div>
                        </div>

                    </div>
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    @include('admin.layout.alert')
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>labour Name :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ $labour_detail['data_users']['full_name'] }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Mobile Number :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users']['mobile_number']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Mnrega ID :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users']['mgnrega_card_id']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>District :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users']['district_id']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Taluka :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users']['taluka_id']) }}</label>
                                        </div>
                                    </div>


                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Village :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users']['village_id']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Latitude :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users']['latitude']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Longitude :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users']['longitude']) }}</label>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Start Date :</label>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8">
                                            <label>{{ strip_tags($labour_detail['data_users']['start_date']) }}</label>
                                        </div>
                                    </div>

                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Profile Image :</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <img class="preview-image" src="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users']['profile_image'] }}"
                                                style="width:100px; height:100px;" />
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 download_btn">
                                        <a href="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users']['profile_image'] }}" download>
                                            <button class="btn btn-primary download-image" style="position: absolute; top: 5px; right: 45px;"><i class="fas fa-download"></i></button>
                                        </a>
                                        </div>
                                    </div>
                                    </br>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Addhar Card :</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <img class="preview-image" src="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users']['aadhar_image'] }}"
                                                style="width:100px; height:100px;" />
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 download_btn">
                                        <a href="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users']['aadhar_image'] }}" download>
                                            <button class="btn btn-primary download-image" style="position: absolute; top: 5px; right: 45px;"><i class="fas fa-download"></i></button>
                                        </a>
                                        </div>
                                    </div>
                                    </br>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Voter Card :</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <img class="preview-image" src="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users']['voter_image'] }}"
                                                style="width:100px; height:100px;" />
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 download_btn">
                                        <a href="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users']['voter_image'] }}" download>
                                            <button class="btn btn-primary download-image" style="position: absolute; top: 5px; right: 45px;"><i class="fas fa-download"></i></button>
                                        </a>
                                        </div>
                                    </div>
                                    </br>
                                    <div class="row ">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <label>Mgnrega Card :</label>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            <img class="preview-image" src="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users']['mgnrega_image'] }}"
                                                style="width:100px; height:100px;" />
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 download_btn">
                                        <a href="{{ Config::get('DocumentConstant.USER_LABOUR_VIEW') }}{{ $labour_detail['data_users']['mgnrega_image'] }}" download>
                                            <button class="btn btn-primary download-image" style="position: absolute; top: 5px; right: 45px;"><i class="fas fa-download"></i></button>
                                        </a>
                                        </div>
                                        <!-- Modal for Image Preview -->
                                        <div id="imageModal" class="modal">
                                            <span class="close">&times;</span>
                                            <img class="modal-content" id="modalImage">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 grid-margin">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    @include('admin.layout.alert')
                                                    <div class="table-responsive">
                                                        <table id="order-listing" class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sr. No.</th>
                                                                    <th>Full Name</th>
                                                                    <th>Date of Birth</th>
                                                                    <th>Gender</th>
                                                                    <th>Relationship</th>
                                                                    <th>Marital Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- <?php //dd($labour_detail['data_family_users']); ?> -->
                                                            @foreach ($labour_detail['data_family_users'] as $item)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $item['full_name'] }}</td>
                                                                    <td>{{ $item['date_of_birth'] }}</td>
                                                                    <td>{{ $item['gender_name'] }}</td>
                                                                    <td>{{ $item['relation_title'] }}</td>
                                                                    <td>{{ $item['maritalstatus'] }}</td>
                                                                </tr>
                                                            @endforeach
 
                                                            </tbody>
                                                        </table>
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
        <!-- content-wrapper ends -->
        <!-- JavaScript -->
<script>
    // When the user clicks on an image, open the modal
    $(document).on("click", ".preview-image", function() {
        $("#modalImage").attr("src", $(this).attr("src"));
        $("#imageModal").css("display", "block");
    });

    // When the user clicks on the close button or outside the modal, close it
    $(document).on("click", ".close, .modal", function() {
        $("#imageModal").css("display", "none");
    });
</script>
    @endsection



