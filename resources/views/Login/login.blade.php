
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Yinka Enoch Adedokun">
    <title>Login Page</title>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <style type="text/css">
.main-content{
    width: 50%;
    border-radius: 20px;
    box-shadow: 0 5px 5px rgba(0,0,0,.4);
    margin: 5em auto;
    display: flex;
}
.company__info{
    background-color: #D1DDD8;
/*    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;*/
/*    display: flex;
    flex-direction: column;
    justify-content: center;*/
    color: #fff;
}
.fa-android{
    font-size:3em;
}
@media screen and (max-width: 640px) {
    .main-content{width: 90%;}
    .company__info{
        display: none;
    }
    .login_form{
        border-top-left-radius:20px;
        border-bottom-left-radius:20px;
    }
}
@media screen and (min-width: 642px) and (max-width:800px){
    .main-content{width: 70%;}
}
.row > h2{
    color:#008080;
}
.login_form{
    background-color: #E8F3EF;
    border-top-right-radius:2px;
    border-bottom-right-radius:20px;
    border-top:1px solid #ccc;
    border-right:1px solid #ccc;
}
form{
    padding: 0 2em;
}
.form__input{
    width: 100%;
    border:0px solid transparent;
    border-radius: 0;
    border-bottom: 1px solid #aaa;
    padding: 1em .5em .5em;
    padding-left: 2em;
    outline:none;
    margin:1.5em auto;
    transition: all .5s ease;
}
.form__input:focus{
    border-bottom-color: #008080;
    box-shadow: 0 0 5px rgba(0,80,80,.4); 
    border-radius: 4px;
}
.btn{
    transition: all .5s ease;
    width: 70%;
    border-radius: 30px;
    color:#008080;
    font-weight: 600;
    background-color: #fff;
    border: 1px solid #008080;
    margin-top: 1.5em;
    margin-bottom: 1em;
}
.btn:hover, .btn:focus{
    background-color: #008080;
    color:#fff;
}
</style>
</head>
<body>
    <div align="center">
    <a class="margin-0" style="font-family:calibari; color:Red; class=centered; font-size:50px;font-weight:bold;">{{$company_detail->company_name ?? "Company Title"}}</a>
      @php
        $row = App\Companydetail::where('id','1')->first();
      @endphp
    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row main-content bg-success text-center">
            <div class="col-md-5 text-center company__info">
                <span class="company__logo"><h2>
                    <img src="{{asset($row->company_logo)}}" style="margin-left: -10%;">
                </h2></span>
              
            </div>
            <div class="col-md-8 col-xs-12 col-sm-12 login_form ">
                <div class="container-fluid">
                    <div class="row">
                        <h1>Log In</h1>
                    </div>
                    <div class="row">
                        <form action="{{url('/user-check')}}" method="post" class="form-group">
                            @csrf
                            <div class="row">
                                <input type="email" name="email" id="email" class="form__input" placeholder="E-mail">
                            </div>
                            <div class="row">
                                <input type="password" name="password" id="password" class="form__input" placeholder="Password">
                            </div>
                            <div class="row">
                                <input type="submit" value="Submit" class="btn">
                            </div>
                        </form>
                    </div>
                  Developed and Maintained by: Morsalinngn
                  </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <style>
      marquee{
      font-size: 30px;
      font-weight: 50;
      color: #000000;
      font-family: sans-serif;
      }
    </style>
  </head>
  