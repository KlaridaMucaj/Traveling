<!DOCTYPE html>
<html lang="en">

<head>
  <title>Employers</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container">
    <table class="table table-bordered">
      <tr>
        <th>Emri </th>
        <th>Oret nen 8 ore</th>
        <th>Oret mbi 8 ore</th>
        <th>Oret totale</th>
        <th>Pagesa totale</th>
      </tr>
      @foreach ($data as $data)
      <tr>
       <td>{{ $data->full_name }}</td>
       <td>{{ $data->under8hours }}</td>
       <td>{{ $data->over8hours }}</td>
       <td>{{ $data->total_hours }}</td>
       <td>{{ $data->totalPayment }}</td>
      </tr>
      @endforeach
    </table>
  </div>
</body>
</html>