<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Paz y salvo</title>
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
    <p>Contacto: {{$headquarter->telephone ?? $headquarter->telephone ." ".  $headquarter->mobile ?? $headquarter->mobile }}</p>

    <br>
    <h3><strong> PAZ Y SALVO</strong></h3>
    <br>
  </header>
  <section>
    <p colspan="3">
      Certificamos que el(la) señor(a) <strong>{{$client->name}} {{$client->last_name}}</strong> con
      <strong>{{$client->type_document}} {{$client->document}}</strong> es cliente de <strong>{{ $company->name}}
        sede
        {{$headquarter->headquarter}} </strong>, con crédito comercial obligación <strong># {{
        $credit->id}}</strong>, se encuentra a PAZ Y SALVO desde {{$credit->finish_date}}
    </p>

    <p colspan="3">
      Cualquier información adicional, por favor comunicarse al {{$headquarter->phone}}
    </p>

  </section>
  <br>
  <footer>
    <small>Tecnoplus Créditos</small> <br>
    <small>
      Fecha de impresión {{ date('Y-m-d H:i:s A')}}
    </small>
  </footer>


</body>

</html>