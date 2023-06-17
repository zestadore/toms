
@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')
    @section('title')
        <title>TOMS | Invoice</title>
    @endsection
    @section('breadcrump')
        <div class="col-sm-6">
            <h1 class="m-0">Invoice</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('operations.bookings.index')}}">Bookings</a></li>
            <li class="breadcrumb-item active">Invoice</li>
            </ol>
        </div><!-- /.col -->
    @endsection
    @section('content')
    <div class="invoice p-3 mb-3 container" id="invoicePrint">
        <!-- title row -->
        <div class="row">
          <div class="col-12">
            <h4>
              <i class="fas fa-globe"></i> {{$company?->company_name}}.
              <small class="float-right">Date: {{Carbon::parse(Now())->format('d/M/Y')}}</small>
            </h4>
          </div>
          <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            From
            <address>
              <strong>{{$company?->company_name}}.</strong><br>
              {{$company?->address}}<br>
              {{$company?->contact_1}} / {{$company?->contact_2}}<br>
              {{$company?->email_id}}<br>
              {{$company?->url}}
            </address>
          </div>
          <!-- /.col -->
          <div class="col-sm-4 invoice-col">
            To
            <address>
              <strong>{{$agent?->company_name}}</strong><br>
              {{$agent?->address}}<br>
              {{$agent?->contact}}<br>
              {{$agent?->email}}<br>
              {{$agent?->website}}
            </address>
          </div>
          <!-- /.col -->
          <div class="col-sm-4 invoice-col">
            <b>Invoice #IN{{$quotation?->quote_id}}</b><br>
            <b>Booking ID:</b> {{$quotation?->quote_id}}<br>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-12 table-responsive">
            <table class="table table-striped">
              <thead>
              <tr>
                <th>Sl no.</th>
                <th><span style="float:right;">Description</span></th>
              </tr>
              </thead>
              <tbody>
                @if (count($revision?->revisionDetails)>0)
                    @foreach ($revision?->revisionDetails as $item)
                      <tr>
                          <td>{{$loop->iteration}}</td>
                          <td align="right">
                              {{$item->destination?->destination}} - {{$item->hotel?->hotel}}({{$item->roomCategory?->room_category}}) - {{Carbon::parse($item->checkin)->format('d-M-Y')}}
                          </td>
                      </tr>
                    @endforeach
                    @if ($revision->vehicle)
                      <tr>
                        <td>Transportation</td>
                        <td align="right">{{$revision->vehicle?->condition}} {{$revision->vehicle?->vehicle}}</td>
                      </tr>
                    @endif
                @endif
              </tbody>
            </table>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
          <!-- accepted payments column -->
          <div class="col-6">
            <p class="lead">Payment Methods:</p>
            @foreach ($banks as $item)
              <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                  <strong>{{$item?->bank_name}}</strong><br>
                  Name : {{$item?->account_name}} / Acc no : {{$item?->account_namber}}<br>
                  {{$item?->address}} / IFSC : {{$item?->ifsc}}
              </p>
            @endforeach
          </div>
          <!-- /.col -->
          <div class="col-6">

            <div class="table-responsive">
              <table class="table">
                <tbody><tr>
                  <th style="width:50%">Subtotal:</th>
                  <td>₹ {{$revision->grand_total + $revision->markup_amount}}</td>
                </tr>
                @if ($revision?->gst_amount>0)
                  <tr>
                    <th>GST</th>
                    <td>₹ {{$revision?->gst_amount}}</td>
                  </tr>
                @endif
                <tr>
                  <th>Net rate:</th>
                  <td>₹ {{$revision->net_rate}}</td>
                </tr>
              </tbody></table>
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
          <div class="col-12">
            <a href="javascript::void(0)" rel="noopener" class="btn btn-default" id="printButton"><i class="fas fa-print"></i> Print</a>
            <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
              Payment
            </button>
            <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
              <i class="fas fa-download"></i> Generate PDF
            </button>
          </div>
        </div>
      </div>
    @endsection
    @section('scripts')
      <script>
            $("#printButton").click(function () {
                $('#printButton').css('color', 'black');
                window.print();
                $('#printButton').css('color', 'white');
            });
            $(document).ready(function() {
              $("body").removeClass("dark-mode");
            })
      </script>
    @endsection
    