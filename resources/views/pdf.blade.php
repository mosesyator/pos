<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>
  </head>
  <style>
    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }
    
    td, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }
    
    tr:nth-child(even) {
      background-color: #dddddd;
    }
    </style>
  <body>
    <h1>{{$user = Auth::user()->name}}</h1>
    

    <table class="table table-striped table-bordered zero-configuration">
      <thead>
          <tr>
            <th>Product name</th>
            <th>description</th>
            <th>amount </th>
            <th>Price</th>
            <th>Quantity</th>
             
             <th>Submited on</th>
              
          </tr>
      </thead>
      <tbody>
    
      @foreach ($data as $row)
          
  <tr>
        <td>
            {{$row->name}}
            {{$row->description}}
            {{$row->amount}}
            {{$row->price}}
            {{$row->quantity}}
            


        </td>
       
      </tr>
      @endforeach
      </tbody>
    </table>
  </body>
</html>