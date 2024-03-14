@extends('admin.layout.master')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper mt-6">
            <div class="page-header">
                <h3 class="page-title">
                    Add Registration Status
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('list-registrationstatus') }}">Master</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Registration Status</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <form class="forms-sample" action="{{ url('add-registrationstatus') }}" method="POST"
                                enctype="multipart/form-data" id="regForm">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="registrationstatus">Registration Status</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" name="registrationstatus"
                                                id="registrationstatus" value="{{ old('registrationstatus') }}"
                                                placeholder="Enter the Title">
                                            @if ($errors->has('registrationstatus'))
                                                <span class="red-text"><?php echo $errors->first('registrationstatus', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="marathi_title">लिंग</label>&nbsp<span class="red-text">*</span>
                                            <input type="text" class="form-control mb-2" name="marathi_title"
                                                id="marathi_title" value="{{ old('marathi_title') }}"
                                                placeholder="शीर्षक प्रविष्ट करा">
                                            @if ($errors->has('marathi_title'))
                                                <span class="red-text"><?php //echo $errors->first('marathi_title', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div> -->
                                    <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-success" id="submitButton" disabled>
                                            Save &amp; Submit
                                        </button>
                                        {{-- <button type="reset" class="btn btn-danger">Cancel</button> --}}
                                        <span><a href="{{ route('list-registrationstatus') }}"
                                                class="btn btn-sm btn-primary">Back</a></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                // Function to check if all input fields are filled with valid data
                function checkFormValidity() {
                    const registrationstatus = $('#registrationstatus').val();
                    //const marathi_title = $('#marathi_title').val();

                    // Enable the submit button if all fields are valid
                    // if (registrationstatus && marathi_title) 
                    if (registrationstatus) {
                        $('#submitButton').prop('disabled', false);
                    } else {
                        $('#submitButton').prop('disabled', true);
                    }
                }

                // Call the checkFormValidity function on input change
                $('input').on('input change', checkFormValidity);

                // Initialize the form validation
                $("#regForm").validate({
                    rules: {
                        registrationstatus: {
                            required: true,
                        },
                        // marathi_title: {
                        //     required: true,
                        // },
                    },
                    messages: {
                        registrationstatus: {
                            required: "Please Enter the Title",
                        },
                        // marathi_title: {
                        //     required: "कृपया शीर्षक प्रविष्ट करा",
                        // },
                    },
                });
            });
        </script>
    @endsection
