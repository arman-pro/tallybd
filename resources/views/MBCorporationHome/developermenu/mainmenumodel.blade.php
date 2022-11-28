@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')
<br>
<br>
<br>
<br>
<div class="main-content">
  <div class="container-fluid">
    <hr>
<div class="widget-box">
              <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>Edit Main Menu</h5>
          </div>
              			 <div class="widget-content nopadding">
            <form class="form-horizontal" method="post" action="{{URL::to('AdminEditMainlink')}}" name="basic_validate" id="basic_validate" novalidate="novalidate">
             {{ csrf_field() }}

               <div class="control-group" id="ddd">
                <label class="control-label">Serial No (<strong class="text-danger">Must Be English</strong>)</label>
                <div class="controls">
                  <input type="text"
                   onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" 
                    value="{{$data[0]->serialNo}}" placeholder="Serial No" id="dddd" 
                  name="serial">
                </div>
              </div>
           


              <div class="control-group">
              
                <label class="control-label">Menu Name </label>
            
                <div class="controls">
                  <input type="text" placeholder="Menu Name English" name="MenuNameEn"
                   id="MenuNameEn" value="{{$data[0]->Link_Name}}">
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