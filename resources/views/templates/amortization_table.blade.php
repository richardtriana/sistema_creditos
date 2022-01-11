<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 12px;
    }
    .table {
      border-collapse: collapse;
      width: 100%;
    }

    .table th,
    .table td {
      border: 1px solid #E4E4E4;
      padding: 5px;
    }

    .table th,
    table th {
      text-align: left;
    }
    
    .text-center {
            text-align: center !important;
        }
  </style>
</head>

<body>
  <header class="text-center">
    <h3>
      Calendario de pagos
    </h3>
  </header>
  <section>
    <table class="table">
      <tr>
        <td>Cliente: </td>
        <td>Nro. Documento</td>
        <td>Nro. Crédito: {{ $credit->id}}</td>
      </tr>
      <tr>
        <td>Monto: {{ $credit->valor_credit}}</td>
        <td>Nro. Cuotas: {{ $credit->cant_cuotas}}</td>
        <td>Fecha de desembolso: {{ $credit->id}}</td>
      </tr>
      <tr>
        <td>Producto</td>
        <td>Sucursal</td>
        <td>Taasa de Interés</td>
      </tr>
      <tr>
        <td colspan="3"></td>
      </tr>
    </table>
  </section>
  <section>
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
        @foreach ($installments as $key => $f)
        <tr>
          <th>{{$key + 1}}</th>
          <th scope="row">{{ $f->payment_date}}</th>
          <td>{{ $f->valor}}</td>
          <td>{{ $f->valor_pago_capital}}</td>
          <td>{{ $f->valor_pago_interes}}</td>
          <td>{{ $f->late_interests_value}}</td>
          <td>{{ $f->days_past_due}}</td>
        </tr>
        @endforeach

      </tbody>
    </table>
  </section>

</body>

</html>