@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title" style=" font-weight: 800; ">SMS Information</h4>
    </div>
</div>


<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
       
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">SMS Provider Edit</h4>
                </div>
                <div class='card-body'>
                    <form action="{{route('sms.update', ['id'=>$sms->id])}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-5 col-sm-12">
                                <div class="form-group">
                                    <label>Provider Name</label>
                                    <input type="text" name="provider" value="{{$sms->provider}}" class="form-control" placeholder="Provider Name" required />
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-12">
                                <div class="form-group">
                                    <label>Provider URL</label>
                                    <input type="text" name="provider_url" value="{{$sms->provider_url}}" class="form-control" placeholder="Provider URL" required />
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12">&nbsp;</div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-sm-12">
                                <div class="form-group">
                                    <label>SMS Chceck Url</label>
                                    <input type="text" name="sms_check_url" value="{{$sms->sms_check_url}}" class="form-control" placeholder="SMS Check Url" />
                                </div>
                            </div>
                        </div>
                        <div id="box_body">
                            @foreach($sms->sms_options as $key => $sms_option)
                            <?php
                                $rand_digit = rand();
                            ?>
                            <div class="row" id="extra-{{$rand_digit}}">
                                <div class="col-md-5 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="name[]" value="{{$sms_option->name}}" class="form-control" placeholder="Name" required/>
                                    </div>
                                </div>
                                <div class="col-md-5 col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="value[]" value="{{$sms_option->value}}" class="form-control" placeholder="Value" required/>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        @if($key == 2)
                                        <button id="add" class="btn btn-success" type="button">
                                            Add   
                                        </button>
                                        @elseif($key > 2)
                                            <button class="btn btn-danger remove_btn" type="button" data-id="{{$rand_digit}}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-dark">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function(){
        $('#add').on('click', function(){
            let id = Number(Math.random() * 55890).toFixed(0);
            var newOption = '<div class="row" id="extra-'+id+'"><div class="col-md-5 col-sm-12"><div class="form-group">';
                newOption += '<input type="text" name="name[]" class="form-control" placeholder="Name" required/></div></div>';
                newOption += '<div class="col-md-5 col-sm-12"><div class="form-group">';
                newOption += '<input type="text" name="value[]" class="form-control" placeholder="Value" required/></div></div>';
                newOption += '<div class="col-md-2 col-sm-12"><div class="form-group">';
                newOption += '<button class="btn btn-danger remove_btn" type="button" data-id="'+id+'"><i class="fas fa-trash"></i></button></div></div></div>';
            $('#box_body').append(newOption);
        });
        
        $(document).on('click', '.remove_btn', function(){
            
            let id = $(this).data('id');
            $('#extra-'+id).remove();
        });
        
    });
</script>
@endpush



