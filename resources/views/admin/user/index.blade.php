@extends('layouts.app')

@section('content')

<div class="container">
@if(Session::has('message'))
    <div class="content pt0">
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert">X</a>
            <strong>{{ Session::get('message') }}</strong>
        </div>
    </div>
@endif
  <a href="{{ route('user.create') }}" class="btn btn-info">Add New User</a>
  <h2>User List</h2>  
  <table class="table table-bordered" id="user-table" style="text-align:center;">
    <thead>
        <tr class="btn-info"> 
          <th style="text-align: center;">Client ID</th>
          <th style="text-align: center;">Client Name</th>
          <th style="text-align: center;">Notification Email</th>
          <th style="text-align: center;">Active</th>
          <th style="text-align: center;">Created Date</th>
          <th style="text-align: center;">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr style="background-color:  white;">
            <td>{{ $user->id }}</td>
            <td>{{ $user->client }}</td>
            <td>{{ $user->notification_emails }}</td>
            <td> @if($user->active == '1') {{'Yes'}} @else {{'No'}} @endif</td>
            <td>{{ $user->created_at }}</td>
            <td>
                <a href="user/edit/{{$user->id}}"><i class="fa fa-pencil" title = "Edit User"></i></a> &nbsp;
                <!--<span class="del_user"><i class="fa fa-trash" title = "Delete User"></i></span>-->
                <a><span class="del_user" data-custom-value="{{$user->id}}"><i class="fa fa-trash" title = "Remove User" style="cursor:  pointer;"></i></span></a>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
  <div style="text-align: right;">{{ $users->links() }}</div>
</div>
<script>
    $(document).on("click", ".del_user", function () {
        var id = $(this).data("custom-value");
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this user!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: '{{url('delete_client')}}',
                    type: 'POST',
                    data: {'client_id' : id,'_token':$('input:hidden[name=_token]').val()},
                    success: function(data) {
                        swal(data);
                        setTimeout(function(){
                            location.reload();
                        }, 2000);
                    },
                });
            } else {
              swal("Cancelled", "User has not been removed.", "error");
            }
        });
    })
</script>
@stop