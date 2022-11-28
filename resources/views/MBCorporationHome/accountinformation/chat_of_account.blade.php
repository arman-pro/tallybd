@extends('MBCorporationHome.apps_layout.layout')

@section('admin_content')

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title"
                        style=" font-weight: 800; padding-bottom: 10px; border-bottom: 2px solid #eee">Chat Of Account
                    </h4>


                    <div class="container" style="margin-top:30px;">
                        <div class="row">
                            <div class="col-md-12">
                                @php
                                    $assetsPropaty = App\AccountGroup::where('account_group_nature','Assets')->whereNull('account_group_under_id')
                                    ->with('childrenCategories')->with('accountLedgers')->get();
                                    $liabilitiesPropaty  = App\AccountGroup::where('account_group_nature','Liabilities')->whereNull('account_group_under_id')
                                    ->with('childrenCategories')->with('accountLedgers')->get();
                                    $expensesPropaty  = App\AccountGroup::where('account_group_nature','Expenses')->whereNull('account_group_under_id')
                                    ->with('childrenCategories')->with('accountLedgers')->get();
                                    $incomePropaty  = App\AccountGroup::where('account_group_nature','Income')->whereNull('account_group_under_id')
                                    ->with('childrenCategories')
                                    ->with('accountLedgers')
                                    ->get();
                                @endphp
                                <ul id="tree1">
                                    <li><a href="#">Assets</a>
                                        @forelse ($assetsPropaty as $assetsPropaty_row)
                                            <ul>
                                                <li>{{ $assetsPropaty_row->account_group_name }}
                                                    <ul>
                                                        @foreach($assetsPropaty_row->childrenCategories  as $childCategory)
                                                            @include('MBCorporationHome.accountinformation.child_category', ['child_category' => $childCategory])
                                                            <ul>
                                                                @include('MBCorporationHome.accountinformation.child_ledger', ['property' => $childCategory])
                                                            </ul>
                                                        @endforeach
                                                        @include('MBCorporationHome.accountinformation.child_ledger', ['property' => $assetsPropaty_row])
                                                    </ul>

                                                </li>
                                            </ul>
                                        @empty

                                        @endforelse

                                    </li>
                                    <li><a href="#">Liabilities</a>
                                        @forelse ($liabilitiesPropaty as $liabilitiesPropaty_row)
                                            <ul>
                                                <li>{{ $liabilitiesPropaty_row->account_group_name }}
                                                    <ul>
                                                        @foreach($liabilitiesPropaty_row->childrenCategories  as $childCategory)
                                                            @include('MBCorporationHome.accountinformation.child_category', ['child_category' => $childCategory])
                                                            <ul>
                                                                @include('MBCorporationHome.accountinformation.child_ledger', ['property' => $childCategory])
                                                            </ul>
                                                        @endforeach
                                                        @include('MBCorporationHome.accountinformation.child_ledger', ['property' => $liabilitiesPropaty_row])
                                                    </ul>
                                                </li>
                                            </ul>
                                        @empty
                                        @endforelse
                                    </li>
                                    <li><a href="#">Income</a>
                                        @forelse ($incomePropaty  as $incomePropaty_row)
                                            <ul>
                                                <li>{{$incomePropaty_row->account_group_name }}
                                                    <ul>
                                                        @foreach($incomePropaty_row->childrenCategories  as $childCategory)
                                                            @include('MBCorporationHome.accountinformation.child_category', ['child_category' => $childCategory])
                                                            <ul>
                                                                @include('MBCorporationHome.accountinformation.child_ledger', ['property' => $childCategory])
                                                            </ul>
                                                        @endforeach
                                                        @include('MBCorporationHome.accountinformation.child_ledger', ['property' => $incomePropaty_row])
                                                    </ul>
                                                </li>
                                            </ul>
                                        @empty
                                        @endforelse
                                    </li>
                                    <li><a href="#">Expenses</a>
                                        @forelse ($expensesPropaty   as $expensesPropaty_row)
                                            <ul>
                                                <li>{{$expensesPropaty_row->account_group_name }}
                                                    <ul>
                                                        @foreach($expensesPropaty_row->childrenCategories  as $childCategory)
                                                            @include('MBCorporationHome.accountinformation.child_category', ['child_category' => $childCategory])
                                                            <ul>
                                                                @include('MBCorporationHome.accountinformation.child_ledger', ['property' => $childCategory])
                                                            </ul>
                                                        @endforeach
                                                        @include('MBCorporationHome.accountinformation.child_ledger', ['property' => $expensesPropaty_row])
                                                    </ul>
                                                </li>
                                            </ul>
                                        @empty
                                        @endforelse
                                    </li>

                                </ul>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
<style>
.tree, .tree ul {
    margin:0;
    padding:0;
    list-style:none
}
.tree ul {
    margin-left:1em;
    position:relative
}
.tree ul ul {
    margin-left:.5em
}
.tree ul:before {
    content:"";
    display:block;
    width:0;
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    border-left:1px solid
}
.tree li {
    margin:0;
    padding:0px 0px 5px 15px ;
    /* 0px 0px 5px 22px */
    line-height:2em;
    color:#369;
    font-weight:700;
    position:relative
}
.tree ul li:before {
    content:"";
    display:block;
    width:10px;
    height:0;
    border-top:1px solid;
    margin-top:-1px;
    position:absolute;
    top:1em;
    left:0
}
.tree ul li:last-child:before {
    background:#fff;
    height:auto;
    top:1em;
    bottom:0
}
.indicator {
    margin-right:5px;
}
.tree li a {
    text-decoration: none;
    color:#369;
}
.tree li button, .tree li button:active, .tree li button:focus {
    text-decoration: none;
    color:#369;
    border:none;
    background:transparent;
    margin:0px 0px 0px 0px;
    padding:0px 0px 0px 0px;
    outline: 0;
}
</style>
@endpush

@push('js')
    {{-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script> --}}
<script>
    !function ($) {

$.fn.extend({
    treed: function (o) {

      var openedClass = 'mdi mdi-receipt';
      var closedClass = 'mdi mdi-receipt';

      if (typeof o != 'undefined'){
        if (typeof o.openedClass != 'undefined'){
        openedClass = o.openedClass;
        }
        if (typeof o.closedClass != 'undefined'){
        closedClass = o.closedClass;
        }
      };

        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='"+ closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
      tree.find('.branch .indicator').each(function(){
        $(this).on('click', function () {
            $(this).closest('li').click();
        });
      });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});

//Initialization of treeviews

$('#tree1').treed();

$('#tree2').treed({openedClass:'glyphicon-folder-open', closedClass:'glyphicon-folder-close'});

$('#tree3').treed({openedClass:'glyphicon-chevron-right', closedClass:'glyphicon-chevron-down'});

}(window.jQuery);

</script>
@endpush
