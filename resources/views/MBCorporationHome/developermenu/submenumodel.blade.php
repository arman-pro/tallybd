@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<br>
<br>
<br>
<br>



<div class="main-content">
  <div class="container-fluid">
    
<div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>Edit Sub Menu</h5>
          </div>
              			 <div class="widget-content nopadding">
            <form class="form-horizontal" method="post" action="{{URL::to('AdminMainMenuEditcon')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
             {{ csrf_field() }}

             <div class="control-group" id="ddd">
                <label class="control-label">Serial No (<strong class="text-danger">Must Be English</strong>)</label>
                <div class="controls">
                  <input type="text"
                   onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" 
                    value="{{$data[0]->serialno}}" placeholder="Serial No" id="dddd" 
                  name="serial">
                </div>
              </div>
           


            <div class="control-group">
              <label class="control-label">Main Menu Name</label>
              <div class="controls">
                <select  name ="mainMenuName"  id="countryId" class="span6" style='width:220px;'>
                 


                   <option value="{{$data[0]->mainmenuId}}" >{{$data[0]->mainmenu}}</option>

                    @if(count($mainmenu) > 0)
              @foreach($mainmenu as $showData)
               @if($showData->id != $data[0]->mainmenuId)
              <option value="{{$showData->id}}" >{{$showData->Link_Name}}</option>
              @endif
              @endforeach
              @endif    
               
                </select>
               <span id="loader"></span>
              </div>
            </div>


              


              <div class="control-group">
              
                <label class="control-label">Sub Menu Name </label>
            
                <div class="controls">
                  <input type="text" placeholder="Sub Menu Name English" name="submenu"
                   id="submenu" value="{{$data[0]->submenuname}}">
                  <input type="hidden"  name="id" id="id" value="{{$data[0]->id}}">
                </div>
              </div>
              <div class="control-group">
                <label class="control-label">Route Name </label>
                <div class="controls">
                  <input type="text" placeholder="Route Name" name="Route" id="Route"
                    value="{{$data[0]->routeName}}" onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode >= 48 && event.charCode <= 57)" >
                </div>
              </div>

            
             
              <div class="form-actions">
                <input type="submit" value="Edit" class="btn btn-success">
              </div>
            </form>
          </div>
</div> 
</div> 
</div> 

@endsection