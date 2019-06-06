
@extends('layouts.dashboard')

@section('content')

  <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>

 <div class="modal fade" id="deleteModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        
       
          
          {{csrf_field()}}
        <div class="modal-body">
        <p class="text-center">
          Are you sure you want to delete this?
        </p>
            <input type="hidden" name="company_id" id="companyid" value="">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">No, Cancel</button>
          <button type="button" id="delete" class="btn btn-warning">Yes, Delete</button>
        </div>
      
      </div>
    </div>
  </div>
<div class="container">
 <div class="box">
<div class="box-body"> 
    <div class="row profile">
        <div class="col-md-12">
           @if(Session::has('success_msg'))
        <div class="alert alert-success">{{ Session::get('success_msg') }}</div>
        @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                <div class="row">
            				<div class="col-md-6">
            				<div class="bs-example margin">
                        <form id="search-form">
                            <div class="input-group">
                                  <input type="text" id="filter_name" name="filter_name" class="form-control"  placeholder="Search by Company name or Phone #" autocomplete="off">
                                      <span class="input-group-btn">
                                        <button type="button" class="btn btn-info btn-flat">Search</button>
                                      </span>
                            </div>
                                
                        </form>
                    </div>
            				</div>
            				<div class="col-md-6">
                      <div class="bs-example margin">
            				<a href="{{ action('CompanyController@create') }}" class="btn btn-info btn-flat"><span class="glyphicon glyphicon-plus"></span>Add Company</a>
            				</div>
                  </div>
				        </div>
                
				</br>
              </br>
            </div>
                <div class="panel-body">
                    <div class="col-md-8">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                  @if(count($companies)==0)
                          <div class="alert alert-warning">
                              <strong>Sorry!</strong> No Companies Found
                           </div> 
                      @else
                    <table class="table table-hover display dataTable" id="companydatatable">
                              <thead>
                                <tr>
                                  <th scope="col">#</th>
                                   <th>Company Name</th>
                                    <th>Company Phone #</th>
									                 <th></th>
                                </tr>
                              </thead>
                             
                            </table>

                                 @endif
                          
                          </div>
                          <div class="col-md-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>
 </div>
</div>

 <script type="text/javascript">

    $( document ).on( "click", ".delete", function () {

        url = $(this).data('url');
        $('#delete').click(function(){
        location.href = url

        })
      })


$(document).ready(function(){
	$.ajaxSetup({
           headers: {
			 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
			 }
	});
  var dataTable = $('#companydatatable').DataTable({
    'processing': true,
    'serverSide': true,
	bFilter: false, 
  bInfo: false,
   "bLengthChange": false,
   "bSort" : false,
    'serverMethod': 'post',
    //'searching': false, // Remove default Search Control
    'ajax': {
       'url':"{{ route('ajaxfilter') }}",
       'data': function(data){
          // Read values
          var name = $('#filter_name').val();
			var token= $('meta[name="_token"]').attr('content');
          // Append to data
          data.filter_name = name;
		    data._token= "{{ csrf_token() }}";
       }
    },
    'columns': [
       { data: 'id' }, 
       { data: 'company' },
       { data: 'phone_number' },
       { data: 'action' },
    ]
  });

  $('#filter_name').keyup(function(){
    dataTable.draw();
  });

});

</script>
@section('script')

@endsection
@endsection



