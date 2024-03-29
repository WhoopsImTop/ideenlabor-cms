<!DOCTYPE html>
<html>

<head>
  <title>{{ $invoice->invoice_number }}</title>
</head>

<style>
  body {
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    margin-left: 1cm;
    margin-right: 1cm;
  }

  h1 {
    font-size: 24px;
    margin: 0px;
  }

  p {
    font-size: 14px;
    line-height: 26px;
    margin: 0px;
  }

  .contact-information {
    margin: 3cm 0;
  }

  .contact-information p {
    line-height: 18px;
    font-size: 12px;
    margin: 0px;
  }

  .dense-table {
    margin-top: 20px;
    border-collapse: collapse;
    width: 100%;
    border-top: 1px solid black;
    border-bottom: 1px solid black;
    padding: 10px 0;
  }

  .dense-table th {
    font-weight: bold;
    text-align: left;
  }

  tr {
    padding: 0;
  }

  .invoice-table {
    margin: 20px 0;
    border-collapse: collapse;
    width: 100%;
    border: 1px solid black;
  }

  .invoice-table th {
    font-weight: bold;
    text-align: left;
    border: 1px solid black;
    padding: 5px;
  }

  .invoice-table td {
    padding: 5px;
    border: 1px solid black;
  }

  .invoice-table tr {
    border-bottom: 1px solid black;
  }

  .company-logo {
    position: absolute;
    top: 1cm;
    right: 1cm;
  }

  .footer-table {
    width: 100%;
    position: absolute;
    bottom: 2cm;
  }

  .footer-table td {
    padding: 10px;
    border-left: 1px solid black;
  }

  .footer-table td p {
    font-size: 10px;
    line-height: normal;
  }
</style>

<body>
  <div class="container">

    @if($companyData)
    <img class="company-logo" src="{{ public_path(str_replace('public/', '', $companyData->company_image)) }}" alt="{{public_path(str_replace('public/', '', $companyData->company_image))}}" width="150px">
    @endif


    <div class="contact-information">
      @if($companyData)
      <span class="smallCompanyAdress" style="font-size: 10px; text-decoration: underline">{{ $companyData->company_name }} {{ $companyData->company_address }}, {{ $companyData->company_zip . ' ' . $companyData->company_city}}</span>
      @endif
      @if($invoice->customer->customer_company_name)
      <p>{{ $invoice->customer->customer_company_name }}</p>
      @endif
      @if($invoice->customer->customer_name)
      <p>{{ $invoice->customer->customer_name }}</p>
      @endif
      @if($invoice->customer->customer_street)
      <p>{{ $invoice->customer->customer_street }}</p>
      @endif
      @if($invoice->customer->customer_address)
      <p>{{ $invoice->customer->customer_address }}</p>
      @endif
      @if($invoice->customer->customer_zip)
      <p>{{ $invoice->customer->customer_zip }} {{ $invoice->customer->customer_city }}</p>
      @endif
    </div>

    <h1>{{ $invoice->invoice_title }}</h1>
    <p>{{ $invoice->invoice_start_text }}</p>

    <table class="dense-table">
      <thead>
        <tr>
          <th>Rechnungsnr.:</th>
          <th>Kundennr.:</th>
          <th>Datum:</th>
          <th>Lieferdatum:</th>
      </thead>
      <tbody>
        <tr>
          <td>{{ $invoice->invoice_number }}</td>
          <td>{{ $invoice->customer->customer_number }}</td>
          <!-- formatted invoice_date -->
          <td>{{ date('d.m.y', strtotime($invoice->invoice_date)) }}</td>
          @if($invoice->invoice_delivery_date)
          <td>
            {{ date('d.m.y', strtotime($invoice->invoice_delivery_date)) }}
          </td>
          @else
          <td>
            {{ date('d.m.y', strtotime($invoice->invoice_delivery_start_date)) }}
            -
            {{ date('d.m.y', strtotime($invoice->invoice_delivery_end_date)) }}
          </td>
          @endif
        </tr>
      </tbody>
    </table>

    <table class="invoice-table">
      <thead>
        <tr>
          <th>Pos.</th>
          <th style="width: 40%">Bezeichnung</th>
          <th>Menge</th>
          <th>Einheit</th>
          <th>Einzel €</th>
          <th>Rabatt %</th>
          <th style="text-align: right">Gesamt €</th>
        </tr>
      </thead>
      <tbody>
        @foreach(json_decode($invoice->invoice_positions) as $invoice_position)
        <tr>
          <td style="vertical-align: top">
            <p>{{ $invoice_position->position }}</p>
          </td>
          <td style="vertical-align: top">
            <p><strong>{{ $invoice_position->name }}</strong></p>
            {!! $invoice_position->description !!}
          </td>
          <td style="vertical-align: top">
            <p>{{ $invoice_position->quantity }}</p>
          </td>
          <td style="vertical-align: top">
            <p>{{ $invoice_position->unit }}</p>
          </td>
          <td style="vertical-align: top">
            <p>{{ $invoice_position->price }}</p>
          </td>
          @if($invoice_position->discount)
          <td style="vertical-align: top">
            <p>{{ $invoice_position->discount }}</p>
          </td>
          @else
          <td style="vertical-align: top">
            <p>0</p>
          </td>
          @endif
          <td style="vertical-align: top; text-align: right">
            <p>{{ number_format($invoice_position->amount, 2, ',', '.') }}</p>
          </td>
        </tr>
        @endforeach

        <tr>
          <td colspan="6">Zwischensumme</td>
          <td style="text-align: right">
            {{ number_format($invoice->invoice_subtotal, 2, ',', '.') }} €
          </td>
        </tr>

        @if($invoice->invoice_discount)
        <tr>
          <td colspan="6">Rabatt</td>
          <td style="text-align: right">
            {{ number_format($invoice->invoice_shipping, 2, ',', '.') }} €
          </td>
        </tr>
        @endif

        @if($invoice->invoice_shipping)
        <tr>
          <td colspan="6">Versandkosten</td>
          <td style="text-align: right">
            {{ number_format($invoice->invoice_shipping, 2, ',', '.') }} €
          </td>
        </tr>
        @endif

        @if($invoice->invoice_tax)
        <tr>
          <td colspan="6">MwSt. 19%</td>
          <td style="text-align: right">
            {{ number_format($invoice->invoice_tax, 2, ',', '.') }} €
          </td>
        </tr>
        @endif

        <tr>
          <td colspan="6">Gesamtsumme</td>
          <td style="text-align: right">
            {{ number_format($invoice->invoice_total, 2, ',', '.') }} €
          </td>
        </tr>
      </tbody>
    </table>

    @if($invoice->invoice_payment_condition)
    <p>{{ $invoice->invoice_payment_condition }}</p>
    @endif

    @if($invoice->invoice_afterword)
    <p>{!! $invoice->invoice_afterword !!}</p>
    @endif

    <!-- create a footer table with the companyData -->
    @if($companyData)
    <table class="footer-table" style="width: 100%">
      <tbody>
        <tr>
          <td style="vertical-align: top">
            <p>{{ $companyData->company_name }}</p>
            <p>{{ $companyData->company_address }}</p>
            <p>{{ $companyData->company_zip . ' ' . $companyData->company_city }}</p>
            <p>{{ $companyData->company_phone }}</p>
            <p>{{ $companyData->company_email }}</p>
          </td>
          <td style="vertical-align: top">
            <p>{{ $companyData->company_bank_holder }}</p>
            <p>{{ $companyData->company_bank_name }}</p>
            <p>{{ $companyData->company_bank_iban }}</p>
            <p>{{ $companyData->company_bank_bic }}</p>
          </td>
          <td style="vertical-align: top">
            <p>USt-IdNr.: {{ $companyData->company_tax_id }}</p>
            <p>Steuernummer: {{ $companyData->company_vat_id }}</p>
          </td>
        </tr>
      </tbody>
    </table>
    @endif

  </div>
</body>

</html>