<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Aloha!</title>

  <style type="text/css">
    * {
     font-family: DejaVu Sans, sans-serif;
    }
    table{
      font-size: x-small;
    }
    tfoot tr td{
      font-weight: bold;
      font-size: x-small;
    }

    .gray {
      background-color: lightgray
    }
  </style>

</head>
<body>

<table width="100%">
  <tr>
    <td valign="top"><img src="" alt="" width="150"/></td>
    <td align="right">
      <h3>{{ $offer->customer->name }}</h3>
            <pre>
                {{ $offer->customer->address }}
                {{ $offer->customer->city }} {{ $offer->customer->zip }}
                {{ $offer->customer->vat }}
                phone
                fax
            </pre>
    </td>
  </tr>

</table>

<table width="100%">
  <tr>
    <td><strong>From:</strong> Linblum - Barrio teatral</td>
    <td><strong>To:</strong> Linblum - Barrio Comercial</td>
  </tr>

</table>

<br/>

<table width="100%">
  <thead style="background-color: lightgray;">
  <tr>
    <th>#</th>
    <th>Naziv</th>
    <th>Količina</th>
    <th>Cena na enoto</th>
    <th>Popust %</th>
    <th>Skupaj &euro;</th>
  </tr>
  </thead>
  <tbody>

  @foreach($offer->products as $k => $product)
    <tr>
      <td scope="row">{{ $k+1 }}</td>
      <td>{{ $product->name}}</td>
      <td class="right">{{ $product->pivot->qty }} {{ $product->unit }}</td>
      <td align="right">{{ $product->price }} &euro;</td>
      <td align="right">{{ $product->pivot->discount }} %</td>
      <td align="right">{{ $product->pivot->total }} &euro;</td>
    </tr>
  @endforeach
  </tbody>

  <tfoot>
  <tr>
    <td colspan="4"></td>
    <td align="right">Skupaj</td>
    <td align="right">{{ $offer->subtotal }} &euro;</td>
  </tr>
  <tr>
    <td colspan="4"></td>
    <td align="right">Popust</td>
    <td align="right">{{ $offer->subtotal_discount }} &euro;</td>
  </tr>
  <tr>
    <td colspan="4"></td>
    <td align="right">DDV</td>
    <td align="right">{{ $offer->subtotal_vat }} &euro;</td>
  </tr>
  <tr>
    <td colspan="4"></td>
    <td align="right">Za plačilo</td>
    <td align="right">{{ $offer->total }} &euro;</td>
  </tr>
  </tfoot>
</table>

</body>
</html>
