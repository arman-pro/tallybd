@extends('MBCorporationHome.apps_layout.layout')
@section('title', 'Update Godwn')
@section('admin_content')

<div class="container-fluid">
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="godown">
    <div class="col-md-6 m-auto col-sm-12">
        <div class="card">
            <div class="card-header bg-success">
                <h4 class="card-title">Update Company Godwn</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{url('/update_godown/'.$godown->id)}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="cono1" class="control-label col-form-label">Godown Name :</label>
                        <input type="text" name="name" class="form-control" id="cono1" value="{{$godown->name}}" />
                    </div>
                    <div class="form-group">
                        <label for="cono1" class="control-label col-form-label">Description :</label>
                        <textarea class="form-control" name="description">{{$godown->description}}</textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" >Update Godwn</button>&nbsp;
                        <a href="{{route('mb_cor_index')}}" class="btn btn-danger">Cencel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
