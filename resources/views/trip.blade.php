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
<form action="/price" method="GET" enctype="multipart/form-data" id="myform" name="myform">
    @csrf
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">
                Welcome, please select a company and enter the distance,the time for starting and ending the trip!
            </a></nav>
        <table class="table">
            <tr>
                <td>
                    <select name="company" id="company" class="company form-control">
                        <option value="">Select Company</option>
                        <option value="1">Company A</option>
                        <option value="2">Company B</option>
                        <option value="3">Company C</option>
                    </select></td>

                <td><input class="form-control"  name="distance" type="number" placeholder="Distance" min="1"></td>
                <td><input class="form-control" name="startDate" type="datetime-local" ></td>
                <td><input class="form-control" name="endDate" type="datetime-local" ></td>
                <td><input class="btn btn-primary" type="submit" value="Submit"></td>
            </tr>
        </table>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">{{'Price by kilometres '}}<b>{{ $priceByKm}}{{' Euro'}}</b></a>
        </nav>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">{{'Price by time '}}<b>{{ $priceByTime}}{{' Euro'}}</b></a>
        </nav>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Thank you :)</a>
        </nav>
    </div>
</form>
</body>
</html>
