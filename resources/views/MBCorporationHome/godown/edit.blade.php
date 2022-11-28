@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="card">
    <div class="card-body">
        <h4 class="card-title" style=" font-weight: 800; "> Company Godwn List</h4>
    </div>
</div>

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="godown">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="border: 1px solid #69C6E0;border-radius: 5px;">

                    <form action="{{url('/update_godown/'.$godown->id)}}" method="POST">
                        @csrf


                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif


                        <h4 class="card-title"
                            style=" font-weight: 600; padding-bottom: 10px;background-color: #69C6E0; padding: 5px 20px;color: #fff;border-radius: 5px;">
                            Update Company Godwn</h4><br>
                        <br>
                        <div class="godown">

                            <div class="col-md-6">
                                <div class="form-group godown">
                                    <label for="cono1" class="control-label col-form-label">Godown Name :</label>
                                    <div>
                                        <input type="text" name="name" class="form-control" id="cono1"
                                            value="{{$godown->name}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group godown">
                                    <label for="cono1" class="control-label col-form-label">Description :</label>
                                    <div>
                                        <textarea class="form-control" name="description">
					                        	{{$godown->description}}
					                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <br>
                        <br>
                        <div style="text-align: center; color: #fff; font-weight: 800;">
                            <button type="submit" class="btn btn-primary"
                                style="width: 200px;color:#fff; font-weight: 800;font-size: 18px;">Update Godwn</button>
                            <a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div>

        </div>
        @endsection
