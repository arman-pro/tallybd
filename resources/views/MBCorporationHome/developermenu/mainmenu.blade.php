@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> --}}
<br>
<br>
<br>
<br>
<div class="main-content">
  <div class="container-fluid">
    <hr>

<div class="modal fade" id="myModal" role="dialog" >
  <div class="modal-dialog modal-lg" >
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Data</h4>
      </div>
      <div class="modal-body" >
        <p>
                

        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    
  </div>
</div>



<div class="row-fluid">
      <div class="span10">
        @include('msg.flash')
 <div class="widget-box">
          <div class="widget-title">
  {{--           <ul class="nav nav-tabs">
              <li class="active"><a data-toggle="tab" href="#tab1" class="btn btn-info">ADD</a></li>&nbsp;&nbsp;
              <li><a data-toggle="tab" href="#tab2" class="btn btn-info">View</a></li>
             
            </ul> --}}
          </div>
          <div class="widget-content tab-content">
            {{-- <div id="tab1" class="tab-pane active"> --}}
              <p>
    <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>Add Main Menu</h5>
          </div>
                     <div class="widget-content nopadding">
            <form class="form-horizontal" method="post" action="{{URL::to('AdmainSaveMainlink')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
             {{ csrf_field() }}

             <div class="control-group" id="staticParent">
                <label class="control-label">Serial No (<strong class="text-danger">Must Be English</strong>)</label>
                <div class="controls">
                  <input type="text"  class='form-control'  value="{{old('serial')}}" placeholder="Serial No"
                   id="child" name="serial">
                </div>
              </div>


              <div class="control-group">
              
                <label class="control-label">Link Name </label>
            
                <div class="controls">
                  <input type="text" class='form-control' placeholder="Menu Name English" name="MenuNameEn" id="MenuNameEn" value="{{old('MenuNameEn')}}">
                </div>
              </div>
            

             

               <div class="control-group" id="staticParent">
                <label class="control-label">Route Name (<strong class="text-danger"> Must Be English</strong>)</label>
                <div class="controls">
                  <input type="text"   
                  value="{{old('Route')}}" placeholder="Route Name " class='form-control'  
                  id="child" name="Route" onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode >= 48 && event.charCode <= 57)">
                </div>
              </div>


           
             
              <div class="form-actions">
                <input type="submit" value="Save" class="btn btn-success">
              </div>
            </form>
          </div>
             </div>  </p>
       {{--      </div>
            <div id="tab2" class="tab-pane"> --}}
              <p>
                           <div class="widget-box">
   <form action="#" method="get" class="form-horizontal">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>View Information</h5>
          </div>
          <div class="widget-content nopadding">
            <table id="example" class="display nowrap" style="width:100%">
              <thead>
                <tr>
                  <th>Serial No</th>
                  <th>Link Name</th>
                   <th>Route Name</th>
                  <th>Action</th>
                </tr>
              </thead> 
              <tfoot>
                <tr>
                  <th>Serial No</th>
                  <th>Link Name</th>
                   <th>Route Name</th>
                  <th>Action</th>
                </tr>
              </tfoot>
        
              <tbody>
                @if(count($mainMenu) > 0)
                @foreach($mainMenu as $showDAta)
               <tr class="gradeX" id="tr-{{$showDAta->id}}">
                  <td>{{$showDAta->serialNo}}</td>
                  <td>{{$showDAta->Link_Name}}</td>
                  <td>{{$showDAta->routeName}}</td>
                  <td>
                  <a href="{{url('/AdminMainMenuModel')}}/{{$showDAta->id}}" 
                      class="btn btn-primary btn-mini">Edit</a>
                    <a class="btn btn-danger btn-mini" onclick="deteDate('{{$showDAta->id}}')" >
                      Delete</a></td>
                </tr>
         
                @endforeach
                       @endif
            
           </tbody>
            </table>
          </div>
          </form>
        </div>
  
           </p>
            {{-- </div> --}}
           
          </div>
        </div>
        </div>
        </div></div>
        </div>

         <meta name="_token" content="{!! csrf_token() !!}" />
        <script type="text/javascript">
        function loadModel(id)
        {
          $(".modal-body").load("{{URL::to('AdminMainMenuModel')}}"+'/'+id);
        }

          $(function() {
  $('#staticParent').on('keydown', '#child', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
})










 function deteDate(getId){
            
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
            })

             $.ajax({

            type: "POST",
            url: "{{URL::to('adminDeleteData')}}/"+getId,
            data: {id:getId},
            dataType: 'json',
            success: function (data) {
               //console.log(data);
                $("#tr-"+getId).hide();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });

          }

        </script>
@endsection
