<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link  href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<body>
<form action="/checkin" method="POST" enctype="multipart/form-data" id="myform" name="myform">
    @csrf
<div class="container">
{{--    <table class="table table-bordered">--}}
{{--        <tr>--}}
{{--            <td>--}}
{{--                <select  id="nameid" name='user_id' class="form-control h-8  text-black font-semibold">--}}
{{--                    <option></option>--}}

{{--                    @foreach ($users as $user)--}}
{{--                        <option value="{{ $user->id }}">{{ $user->full_name }}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--                @error('user_id')--}}
{{--                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>--}}
{{--                @enderror--}}
{{--            </td>--}}
{{--            <td><input class="form-control" name="date" type="date" ></td>--}}

{{--        <td>--}}
{{--                <button type="submit" class="btn btn-success">Search</button>--}}
{{--        </td>--}}
{{--        </tr>--}}
{{--        </table>--}}

        <table class="table">
            <tr>
                <td><input class="form-control" name="from_date" type="date" ></td>
                <td><input class="form-control" name="to_date" type="date" ></td>
                <td>
                    <select name="status" id="status" class="status form-control">
                        <option value="">Select Status</option>
                        <option value="1">Active</option>
                        <option value="0">Deactive</option>
                    </select></td>
            <td>
                <select name="filter" id="filter" class="filter form-control">
                    <option value="">Select Filter</option>
                    <option value="1">Filter by name</option>
                    <option value="2">Filter by id</option>
                    <option value="3">Filter by date</option>
                </select></td>
            <td><input class="form-control" name="search" type="search" ></td>
            <td>    <select name="operator" id="operator" class="operator form-control">
                    <option value="=">=</option> //ok
                    <option value="!=">!=</option>
                    <option value="<"><</option>  //ok
                    <option value=">">></option>
                    <option value="<="><=</option>  //ok
                    <option value=">=">>=</option>
                </select></td>
        </tr>
    </table>

{{--    <div class="col-md-12">--}}
{{-- <input class="p-3 rounded bg-blue-500 mt-3 btn-default pull-right" type="submit" value="Filter">--}}
{{--    </div>--}}

    <table class="table table-bordered user_datatable">
        <thead>
        <tr>
            <th>Id </th>
            <th>Name</th>
            <th>Total paga</th>
            <th>Total checkin</th>
            <th>Status</th>
{{--            <th>Date</th>--}}
        </tr></thead>
    </table>
</div>
</form>
</body>
<script type="text/javascript">

        var table = $('.user_datatable').DataTable({
        processing: true,
        serverSide: true,
            "searching": false,
        ajax:{
        url: '{{ route('usersCheckin') }}',
        data:function (d){
        d.from_date=$('input[name=from_date]').val();
        d.to_date=$('input[name=to_date]').val();
        d.status = $('#status').val();
        d.search = $('input[type="search"]').val();
        d.operator = $('#operator').val();
    }
    },

        columns: [
     {data: 'id', name: 'id'},
     {data: 'full_name', name: 'full_name'},
     {data: 'total_paga', name: 'total_paga'},
     {data: 'total_in', name: 'total_in'},
     {data: 'status', name: 'status'},
     //{data: 'check_in_date', name: 'check_in_date'},
        ]
    });

    //     $('#myform').on('submit',function(e){
    //     table.draw();
    //     e.preventDefault();
    //
    // });

        $('#myform').change(function(){
            table.draw();

        })

        $('#status').change(function(){
            table.draw();
        });

        $('#operator').change(function(){
            table.draw();
        });


    $("#filter").on("change", function() {
        var currValue = parseInt($(this).val());
        $("#operator option").each((index, item) => {

            if ($( "#filter option:selected" ).text() === 'Filter by name'){
                if ($(item).text() !== '=' && $(item).text() !== '!=')
                $(item).attr("disabled", "disabled");
                }
            else{
                $("#operator option").removeAttr("disabled");
            }
     });
    });
</script>
</html>


