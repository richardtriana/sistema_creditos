<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Fecha</th>
        <th scope="col">Valor</th>
        <th scope="col">Capital</th>
        <th scope="col">Interés</th>
        <th scope="col">Mora</th>
        <th scope="col">Días de mora</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($fees as $key => $f)
      <tr>
        <th>{{$key + 1}}</th>
        <th scope="row">{{ $f->fecha_pago}}</th>
        <td>{{ $f->valor}}</td>
        <td>{{ $f->valor_pago_capital}}</td>
        <td>{{ $f->valor_pago_interes}}</td>
        <td>{{ $f->valor_interes_mora}}</td>
        <td>{{ $f->dias_mora}}</td>
      </tr>
      @endforeach

    </tbody>
  </table>

</body>

</html>