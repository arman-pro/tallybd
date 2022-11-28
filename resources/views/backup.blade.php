@extends('MBCorporationHome.apps_layout.layout')
@push('css')

@section('admin_content')
<div style="background: #fff;">
    <h3 style="height:50px;text-align: center; padding-top: 10px;border-bottom: 3px solid #eee;">Database BackUp System</h3>
    <div class="row">
   
        <div class="card">
            <div class="card-header  text-center">
                <i class="fa fa-database" aria-hidden="true"></i>  Database Backup Cron Job Url
            </div>
            <div class="card-body">
                <mark>
                    wget -q -O - http://yourdomain.com/database/backup/download >/dev/null 2>&1
                </mark>

            </div>
        </div>
        <form action="{{url('/database/backup/download')}}" method="GET">
            <div class="row">

                <div class="col-md-12" style="text-align: center;">
                    <br>
                    <button type="submit" class="btn btn-success"
                        style="color: #fff;font-size:16px;font-weight: 800;">Click Database Backup</button>
                    <a href="{{route('gdrive')}}" class="btn btn-outline-dark">Upload Google Driver</a>
                </div>
            </div>
        </form>
        <br>
        <div class="container table-responsive py-5">
          <div class="card">
              <div class="card-header text-center">
                 <h4> Database Backup History</h4>
              </div>
              <div class="card-body">
                <table class="table table-bordered table-hover" >
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Sl.</th>
                            <th scope="col">Database</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($files as $key=>$file)
                            <tr>
                                <th scope="row">{{ $key+1 }}</th>
                                <td>{{ $file}}</td>
                                <td><a href="{{ url('backup/download/single', $file) }}" class="btn btn-success btn-sm">
                                <i class="fa fa-download text-white" aria-hidden="true"></i>
                                </a>
                                <a href="{{ url('backup/delete/single', $file) }}" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash text-white" aria-hidden="true"></i>
                                </a>
                            </td>
                            </tr>
                        @empty
                        @endforelse


                    </tbody>
                </table>
              </div>

          </div>
        </div>



    </div>
</div>

@endsection
