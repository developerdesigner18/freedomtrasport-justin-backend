<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.5;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .container {
            padding: 20px;
            flex: 1; /* Allows the container to expand and leave room for the footer at the bottom */
        }

        .header img {
            max-width: 150px;
        }

        .details-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse; /* Ensures clean table layout */
        }

        .details-table td {
            vertical-align: top;
            padding: 5px;
        }

        .details-table .column {
            width: 33%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        .footer {
            width: 100%;
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
        }

        .footer-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .footer-table td {
            vertical-align: top;
            padding: 5px;
        }

        .footer-table .left-column {
            width: 60%;
        }

        .footer-table .right-column {
            width: 40%;
            text-align: right;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header Section -->
    <div class="header">
        <img src="http://cms.freedomtransport.com.au/images/new-pdf-logo.png"
             alt="Freedom Transport Logo">
    </div>

    <!-- Details Section -->
    <table class="details-table">
        <tr>
            <!-- Left Column -->
            <td class="column">
                <strong>From</strong><br>
                Freedom Transport<br>
                3 Kokomo St<br>
                Peregian Beach, QLD 4573<br>
                Phone: 0412 445 967<br>
                Email: contact@freedomtransport.com.au<br>
                ABN: 96309591350
            </td>

            <!-- Middle Column -->
            <td class="column">
                <strong>To</strong><br>
                {{$user->name}}<br>
                {{@$user->userDetails->address}}<br>
                Phone: {{$user->mobile}}<br>
                Email: {{$user->email}}
            </td>

            <!-- Right Column -->
            {{--            {{dd($rideDetails)}}--}}
            <td class="column">
                <strong>Tax Invoice #{{$invoiceNumber}}</strong><br>
                Issue Date: {{Carbon\Carbon::parse($rideDetails->created_at)->format('d/m/Y')}}<br>
{{--                Payment Due: {{Carbon\Carbon::parse($rideDetails->completed_at)->format('d/m/Y')}}<br>--}}
                Payment Due: {{ Carbon\Carbon::parse($rideDetails->completed_at)->addDays(14)->format('d/m/Y') }}<br>
                @if($user->userNdis)
                    NDIS : {{$user->userNdis->number}}
                @endif

                @if($user->userNiisq)
                    NIISQ : {{$user->userNiisq->number}}
                @endif

                @if($user->userAgedCare)
                    AGED CARE : {{$user->userAgedCare->number}}
                @endif

                {{--                @if($user->userPrivate)--}}
                {{--                    Private Details :--}}
                {{--                @endif--}}

            </td>
        </tr>
    </table>

    <!-- Table Section -->
    <table>
        <thead>
        <tr>
            <th>Description</th>
            <th>Type</th>
            <th>Quantity</th>
            <th>Rate</th>
{{--            <th>Tax</th>--}}
            <th>Cost</th>
        </tr>
        </thead>
        <tbody>

        @if($userAdvance->line_item_km && $userAdvance->line_item_km != "" && $rideDetails->requestBill->base_price != "0")
            <tr>
                <td>{{$userAdvance->line_item_km}}</td>
                <td>Kms</td>

                <td>{{$rideDetails->total_distance}}</td>
                <td>{{ $rideDetails->currency }} {{ $rideDetails->requestBill->distance_price }}</td>
                {{--            <td>{{ $rideDetails->currency }} {{ number_format(($rideDetails->requestBill->service_tax), 2) }}</td>--}}
                <td>{{ $rideDetails->currency }} {{$rideDetails->total_distance * $rideDetails->requestBill->distance_price}}</td>
            </tr>
        @endif

        @if($userAdvance->line_item_time && $userAdvance->line_item_time != "" && $rideDetails->requestBill->time_price != "0")
            <tr>
                <td>{{$userAdvance->line_item_time}}</td>
                <td>Hours</td>
                <td> {{ (int)$rideDetails->requestBill->total_time + (int)$rideDetails->requestBill->calculated_waiting_time }}min
                </td>
                <td>{{ $rideDetails->currency }} {{ $rideDetails->requestBill->price_per_time }}</td>
                {{--            <td>{{ $rideDetails->currency }} 0.00</td>--}}
                <td>{{ $rideDetails->currency }} {{ ((int)$rideDetails->requestBill->total_time + (int)$rideDetails->requestBill->calculated_waiting_time) * $rideDetails->requestBill->price_per_time  }}</td>
            </tr>
        @endif

        @if($userAdvance->line_item_base && $userAdvance->line_item_base !="")
            <tr>
                <td>{{$userAdvance->line_item_base}}</td>
                <td>Base</td>
                <td> 1
                </td>
                <td>{{ $rideDetails->currency }} {{$rideDetails->requestBill->base_price}}</td>
                {{--            <td>{{ $rideDetails->currency }} 0.00</td>--}}
                <td>{{ $rideDetails->currency }} {{$rideDetails->requestBill->base_price}}</td>
            </tr>
        @endif

        </tbody>
    </table>
</div>

<!-- Footer Section -->
<div class="footer">
    <table class="footer-table">
        <tr>
            <!-- Left Column -->
            <td class="left-column">
                @php
                    $payment = 'Cash';
                @endphp
                @if ($rideDetails->payment_opt == 1)
                    @php
                        $payment = 'Cash';
                    @endphp
                @elseif($rideDetails->payment_opt
                == 0)
                    @php
                        $payment = 'Card';
                    @endphp
                @elseif($rideDetails->payment_opt
                == 2)
                    @php
                        $payment = 'Card';
                    @endphp
                @endif

{{--                <strong>Payment Method: Paid by {{ $payment }}</strong><br>--}}
                Payment Terms: Net 14 days<br>
                (Please use invoice # as reference)<br>
                BSB: 034-185 A/c: 217147
            </td>

            <!-- Right Column -->
            <td class="right-column">
                <strong>Amount Due</strong><br>
                Subtotal: {{$rideDetails->currency}} {{
                    ($rideDetails->requestBill->base_price +
                    $rideDetails->requestBill->distance_price)
                    +
                    ($rideDetails->requestBill->time_price + $rideDetails->requestBill->waiting_charge)
                }}<br>
                Tax: {{$rideDetails->currency}} {{ number_format(($rideDetails->requestBill->service_tax), 2) }}<br>
                Total: {{$rideDetails->currency}} {{
                        ($rideDetails->requestBill->base_price +
                        $rideDetails->requestBill->distance_price)
                        +
                        ($rideDetails->requestBill->time_price + $rideDetails->requestBill->waiting_charge)
                        +
                        (number_format(($rideDetails->requestBill->service_tax), 2))
                    }}
            </td>
        </tr>
    </table>
</div>
</body>
</html>
