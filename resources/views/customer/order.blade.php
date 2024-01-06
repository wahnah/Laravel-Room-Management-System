@extends('template.invoicemaster')
@section('title', 'Food Order Ticket')
@section('head')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Maven+Pro&display=swap');

        body {
            font-family: 'Maven Pro', sans-serif;
        }

        hr {
            color: #0000004f;
            margin-top: 5px;
            margin-bottom: 5px
        }

        .add td {
            color: #c5c4c4;
            text-transform: uppercase;
            font-size: 12px
        }

        .content {
            font-size: 14px
        }

    </style>
@endsection
@section('content')

    <div class="container mt-5 mb-3">
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="d-flex flex-row p-2"> <img src="{{ asset('img/logo/sip.png') }}" width="48">
                        <div class="d-flex flex-column"> <span class="font-weight-bold">Food Order Ticket</span>

                        </div>
                    </div>
                    <hr>
                    <div class="products p-2">
                        <table class="table table-borderless">
                            <tbody>
                                <tr class="add">
                                    <td>Order Description</td>


                                </tr>
                                <tr class="content">
                                    <td>{{ $query }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <hr>
                    <div class="address p-2">
                        <table class="table table-borderless">
                            <tbody>
                                <tr class="add">
                                    <td>Customer Details</td>
                                </tr>
                                <tr class="content">
                                    <td>
                                        Customer ID : {{ $customer->id }}
                                        <br>Customer Name : {{ $customer->name }}
                                        <br> Customer Room Number : {{ $room->number }}
                                        <br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
