@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<br>
<br>
<br>
<br>



<div class="main-content">
  <div class="container-fluid">

    
    <div class="row-fluid">
      <div class="span10">
@include('msg.flash')
 <div class="widget-box">

          <div class="widget-content tab-content">
      
              <p>
    <div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>Add Sub Menu</h5>
          </div>
              			 <div class="widget-content nopadding">
            <form class="form-horizontal" method="post" action="{{URL::to('AdminSubLinkSave')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
             {{ csrf_field() }}
              
              <div class="control-group" id="staticParent">
                <label class="control-label">Serial No (<strong class="text-danger">Must Be English</strong>)</label>
                <div class="controls">
                  <input type="text"  placeholder="Serial No"  class="form-control"  
                   value="{{old('serial')}}" placeholder="Serial No" id="child" name="serial">
                </div>
              </div>

         
            <div class="control-group">
              <label class="control-label">Main Menu Name</label>
              <div class="controls">
                <select  name ="mainMenuName"  id="countryId" class="form-control">
                  <option >Select One</option>
              @if(count($mainmenu) > 0)
              @foreach($mainmenu as $showData)
              <option value="{{$showData->id}}" >{{$showData->Link_Name}}</option>

              @endforeach
              @endif      
               
                </select>
               <span id="loader"></span>
              </div>
            </div>



              <div class="control-group">
              
                <label class="control-label">Sub Menu Name </label>
            
                <div class="controls">
                  <input type="text" placeholder="Sub Menu Name English"
                   name="SubMenuEn" id="SubMenuEn" class="form-control" value="{{old('SubMenuEn')}}">
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
      
              <p>
              						 <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>View All Data</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table" id="example" class="display nowrap">
              <thead>

                <tr>
                  <th>Serial NO</th>
                  <th>Main Menu Name </th>
                  <th>Sub Menu  Name </th>
                  <th> Route  Name </th>
                  <th>Action</th>
                 
                </tr>
              </thead>
              <tfoot>

                <tr>
                  <th>Serial NO</th>
                  <th>Main Menu Name </th>
                  <th>Sub Menu  Name </th>
                  <th> Route  Name </th>
                  <th>Action</th>
                 
                </tr>
              </tfoot>
              <tbody>
                  
                @if(count($users) > 0)
                @foreach($users as $showDataSub)
                  <tr  id="tr-{{$showDataSub->id}}">
                      <td>{{$showDataSub->serialno}}</td>
                      <td>{{$showDataSub->Link_Name}}</td>
                      <td>{{$showDataSub->submenuname}}</td>
                      <td>{{$showDataSub->routeName}}</td>
                      <td>
                      <div class="fr"> 
                  <a href="{{url('/adminSubModelEdit')}}/{{$showDataSub->id}}" class="btn btn-primary btn-mini"  >Edit</a>
                    <a class="btn btn-danger btn-mini" onclick="deleteData('{{$showDataSub->id}}')" >
                      Delete</a></div>
                    </td>
                  </tr> 
                  @endforeach
                  @endif





             
              </tbody>
            </table>
          </div>
        </div>
  
           </p>
    
           
          </div>
        </div>
        </div>
        </div></div>
        </div>

         <meta name="_token" content="{!! csrf_token() !!}" />
        <script type="text/javascript">
        function loadModel(id)
        {
        	$(".modal-body").load("{{URL::to('adminSubModelEdit')}}"+'/'+id);
        }


 $(function() {
  $('#staticParent').on('keydown', '#child', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
})


function deleteData(getId){
            
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
            })

             $.ajax({

            type: "POST",
            url: "{{URL::to('AdminSubmenuDelete')}}/"+getId,
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
        <script src="{{URL::to('/')}}/public/js/matrix.tables.js"></script>
@endsection