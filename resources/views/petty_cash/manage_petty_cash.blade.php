@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title col-sm-10">
                        Petty Cash List  </h3> 
                        <button class="btn btn-md btn-success pull-right col-sm-2" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Petty Cash
                        </button>
                </div>
                <div class="card-body">
                  <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-info text-center">
                            <th>SL</th>
                            <th>Name</th>
                            <th>Given Amount</th>
                            <th>Given Date</th>
                        </tr>
                    </thead>
                    <tbody>
                      @php $i = 0; @endphp
                        @foreach ($petty_cash as $v_data)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $v_data->user->name }}</td>
                            <td>{{$v_data->amount}}</td>
                            <td>{{$v_data->given_date}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="modal-add">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Add Petty Cash</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('store-petty-cash')}}" method="post" enctype="multipart/form-data">
            @csrf
        <div class="modal-body">
            <div class="row">
              <div class="col-lg-12">
                {{-- <div class="mb-3">
                    <label class="form-label">Company(optional)</label>
                   <select name="company_id" id="company" class="form-control" onchange="getUser();">
                    <option value="">Select Company</option>
                      @foreach($company as $v_company)
                        <option value="{{$v_company->id}}">{{$v_company->name}}</option>
                      @endforeach
                   </select>
                </div> --}}
                <div class="mb-3">
                  @if(auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin')
                  <label class="form-label">User <span style="color: red">*</span> </label>
                  <select name="user_id" id="user_id" class="form-control" required>
                   <option value="">Select User</option>
                     @foreach($user as $v_user)
                       <option value="{{$v_user->id}}">{{$v_user->name}}</option>
                     @endforeach
                  </select>
                  @else
                  <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                  @endif
                  
                </div>
                <div class="mb-3">
                    <label class="form-label">Cash Amount  <span style="color: red">*</span></label>
                    <input type="text" class="form-control" name="amount"
                        placeholder="Type Petty Cash Amount" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date <span style="color: red">*</span></label>
                    <input type="date" class="form-control" name="date"
                        placeholder="" value="{{date('Y-m-d')}}" required>
                </div>
            </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection

<script>
  function getUser(){
    var company = document.getElementById('company').value;
    var url = "{{route('get-user')}}";
    $.ajax({
          type:"GET",
          url:url,
          data:{company},
          success:function(data){
              $('#user_id').html('<option value="">Select User</option>');
              $.each(data, function(key, value) {
                  $('#user_id').append('<option value="' + value.id + '">' + value.name +
                      '</option>');
              });
          },
      });
  }
</script>