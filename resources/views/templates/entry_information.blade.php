<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Información de pago</title>
  <link rel="stylesheet" href="{{ asset('css/print.css') }}">
</head>

<body>

  <header class="text-center">
    <img src="{{ $company->logo}}" alt="" srcset="" width="150">
    <h2>
      {{ $company->name}}
    </h2>
    <h3>
      Sede {{$headquarter->headquarter}}
    </h3>
    <p>NIT:{{$headquarter->nit}} </p>
    <p>Dirección: {{$headquarter->address}}</p>
    <br>
    <h2><strong>{{ $entry->type_entry}}</strong></h2>
  </header>
  <section>
    <table class="table">
      <tr border=0>
        <td>Nro. Ingreso: </td>
        <td> #{{ $entry->id}}</td>
      </tr>
      @if ($credit)
      <tr>
        <td>Cliente: </td>
        <td>{{$client->name}} {{$client->last_name}}</td>
      </tr>     
      <tr>
        <td>Nro. Documento </td>
        <td>{{$client->type_document}} {{$client->document}}</td>
      </tr>
      <tr>
        <td>Nro. Crédito: </td>
        <td> {{ $credit->id}}</td>
      </tr>
      <tr>
        <td>Monto Crédito: </td>
        <td> $ {{number_format($credit->credit_value, 0, '', '.')  }}</td>
      </tr>
      @endif     
      <tr>
        <td>Monto cancelado: </td>
        <td>$ {{ number_format($entry->price, 0, '', '.') }}</td>
      </tr>
      <tr>
        <td>Fecha: </td>
        <td>{{ $entry->date}}</td>
      </tr>
      @if ($credit)
      <tr>
        <td>Producto: </td>
        <td>{{ $credit->description}}</td>
      </tr>
      <tr>
        <td colspan="3"></td>
      </tr>
      <tr class="font-weight-bold">
        <td>Cupo de crédito </td>
        <td>{{ number_format($client->maximum_credit_allowed, 0, '', '.')  }}</td>
      </tr>
      @endif
    </table>
  </section>
  <br><br>
  <footer>
    <small>Tecnoplus Créditos</small> <br>
    <small>
      Fecha de impresión {{ date('Y-m-d H:i:s A')}}
    </small>
  </footer>

</body>

</html>