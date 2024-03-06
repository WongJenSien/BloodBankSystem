<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <style>
        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 20px;
        }

        .heading-row {
            text-align: center;
            margin-bottom: 20px;
        }

        .content-row {
            display: flex;
            justify-content: space-between;
        }

        .column {
            flex: 1;
        }

        .info {
            margin: 5px 0;
        }

        .label {
            font-weight: bold;
            color: #333;
        }

        .value {
            color: #555;
        }

        .text-dark {
            color: #333;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
        }

        .text-center {
            text-align: center;
        }

        .align-middle {
            vertical-align: middle;
        }

        thead {
            background-color: rgba(252, 186, 186, 0.5);
            color: black;
        }

        .row {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin-bottom: 10px;
            /* Adjust as needed */
        }

        /* Column class */
        .column {
            flex: 1;
            margin-right: 10px;
            /* Adjust as needed */
            background-color: #f8f9fa;
            /* Background color */
            padding: 10px;
            /* Padding around content */
            border: 1px solid #dee2e6;
            /* Border color */
            border-radius: 5px;
            /* Border radius */
        }

        /* Alignment center class */
        .alignment-center {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
    </style>


    <title>Inventory Report</title>
</head>

<body>
    <div class="container">
        <div class="heading-row">
            <h3>Blood Bank System</h3>
        </div>
        <hr>
        <div class="content-row">
            <div class="column left-column">
                <div class="info">
                    <span class="label">Title:</span>
                    <span class="value">Inventory Report</span>
                </div>
            </div>
            <div class="column right-column">
                <div class="info">
                    <span class="label">Date:</span>
                    <span class="value">24-03-2024</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div>
            <div class="row">
                <h3 class="text-dark">TOP 5 STOCK IN - {{ $month }}</h3>
            </div>
            <div class="row">
                <table class="table table-bordered border-black text-center">
                    <thead>
                        <tr>
                            <th class="align-middle" rowspan="2">No</th>
                            <th class="align-middle" rowspan="2">Inventory ID</th>
                            <th class="align-middle" rowspan="2">Event ID</th>
                            <th class="align-middle" rowspan="2">Event Name</th>
                            <th class="align-middle" colspan="4">Quantity</th>
                            <th class="align-middle" colspan="2">Status</th>
                        </tr>
                        <tr>
                            <th>A</th>
                            <th>B</th>
                            <th>O</th>
                            <th>AB</th>
                            <th>Available</th>
                            <th>Not Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockInList as $key => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $key }}</td>
                                <td>{{ $item['eventID'] }}</td>
                                <td>{{ $item['EventName'] }}</td>
                                <td>{{ $item['quantity']['BloodTypeA'] }}</td>
                                <td>{{ $item['quantity']['BloodTypeB'] }}</td>
                                <td>{{ $item['quantity']['BloodTypeO'] }}</td>
                                <td>{{ $item['quantity']['BloodTypeAB'] }}</td>
                                <td>{{ $item['StatusQuantity']['Available'] }}</td>
                                <td>{{ $item['StatusQuantity']['Shipment'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h4 style="color: #dc3545; text-decoration: underline;">Summary</h4>
                {{-- TOTAL NUMBER OF BLOOD RECEIVE --}}
                <h5 class="text-dark text-decoration-underline">Total Number of Blood Receive</h5>
                <div class="row">
                    <div class="row">
                        <!-- VIEW BLOOD - A - TOTAL NUMBER OF RECEIVE -->
                        <div class="column">
                            <!-- TITLE -->
                            <div class="row">
                                <span
                                    style="text-decoration: underline; color: #dc3545; font-weight: bold; text-align: center;">
                                    Blood Type A
                                </span>
                            </div>
                            <!-- QUANTITY -->
                            <div class="row">
                                <span style="color: #343a40; font-weight: bold;"> Positive:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood['aPositive'] }}
                                    </span>
                                </span>
                                <span style="color: #343a40; font-weight: bold;"> Negative:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood['aNegative'] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                        <!-- VIEW BLOOD - B - TOTAL NUMBER OF RECEIVE -->
                        <div class="column">
                            <div class="row">
                                <span
                                    style="text-decoration: underline; color: #dc3545; font-weight: bold; text-align: center;">
                                    Blood Type B
                                </span>
                            </div>
                            <div class="row">
                                <span style="color: #343a40; font-weight: bold;"> Positive:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood['bPositive'] }}
                                    </span>
                                </span>
                                <span style="color: #343a40; font-weight: bold;"> Negative:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood['bNegative'] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- VIEW BLOOD - O - TOTAL NUMBER OF RECEIVE -->
                        <div class="column">
                            <div class="row">
                                <span
                                    style="text-decoration: underline; color: #dc3545; font-weight: bold; text-align: center;">
                                    Blood Type O
                                </span>
                            </div>
                            <div class="row">
                                <span style="color: #343a40; font-weight: bold;"> Positive:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood['oPositive'] }}
                                    </span>
                                </span>
                                <span style="color: #343a40; font-weight: bold;"> Negative:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood['oNegative'] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                        <!-- VIEW BLOOD - AB - TOTAL NUMBER OF RECEIVE -->
                        <div class="column">
                            <div class="row">
                                <span
                                    style="text-decoration: underline; color: #dc3545; font-weight: bold; text-align: center;">
                                    Blood Type AB
                                </span>
                            </div>
                            <div class="row">
                                <span style="color: #343a40; font-weight: bold;"> Positive:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood['abPositive'] }}
                                    </span>
                                </span>
                                <span style="color: #343a40; font-weight: bold;"> Negative:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood['abNegative'] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- TOTAL NUMBER OF BLOOD Available --}}
                <h5 class="text-dark text-decoration-underline">Total Number of Blood Available</h5>
                <div class="row">
                    <div class="row">
                        <div class="column">
                            <div class="row">
                                <span
                                    style="text-decoration: underline; color: #dc3545; font-weight: bold; text-align: center;">
                                    Blood Type A
                                </span>
                            </div>
                            <!-- QUANTITY -->
                            <div class="row">
                                <span style="color: #343a40; font-weight: bold;"> Positive:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_available['aPositive'] }}
                                    </span>
                                </span>
                                <span style="color: #343a40; font-weight: bold;"> Negative:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_available['aNegative'] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                        <!-- VIEW BLOOD - B - TOTAL NUMBER OF RECEIVE -->
                        <div class="column">
                            <div class="row">
                                <span
                                    style="text-decoration: underline; color: #dc3545; font-weight: bold; text-align: center;">
                                    Blood Type B
                                </span>
                            </div>
                            <div class="row">
                                <span style="color: #343a40; font-weight: bold;"> Positive:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_available['bPositive'] }}
                                    </span>
                                </span>
                                <span style="color: #343a40; font-weight: bold;"> Negative:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_available['bNegative'] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- VIEW BLOOD - O - TOTAL NUMBER OF RECEIVE -->
                        <div class="column">
                            <div class="row">
                                <span
                                    style="text-decoration: underline; color: #dc3545; font-weight: bold; text-align: center;">
                                    Blood Type O
                                </span>
                            </div>
                            <div class="row">
                                <span style="color: #343a40; font-weight: bold;"> Positive:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_available['oPositive'] }}
                                    </span>
                                </span>
                                <span style="color: #343a40; font-weight: bold;"> Negative:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_available['oNegative'] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                        <!-- VIEW BLOOD - AB - TOTAL NUMBER OF RECEIVE -->
                        <div class="column">
                            <div class="row">
                                <span
                                    style="text-decoration: underline; color: #dc3545; font-weight: bold; text-align: center;">
                                    Blood Type AB
                                </span>
                            </div>
                            <div class="row">
                                <span style="color: #343a40; font-weight: bold;"> Positive:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_available['abPositive'] }}
                                    </span>
                                </span>
                                <span style="color: #343a40; font-weight: bold;"> Negative:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_available['abNegative'] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <hr>
        <div>
            <div class="row">
                <h3 class="text-dark">TOP 5 STOCK OUT - {{ $month }}</h3>
            </div>
            <div class="row">
                <table class="table table-bordered border-black text-center">
                    <thead>
                        <tr>
                            <th class="align-middle" rowspan="2">No</th>
                            <th class="align-middle" rowspan="2">Shipment ID</th>
                            <th class="align-middle" rowspan="2">Request Date</th>
                            <th class="align-middle" rowspan="2">Location</th>
                            <th class="align-middle" rowspan="2">Shipment Date</th>
                            <th class="align-middle" rowspan="2">Status</th>
                            <th class="align-middle" colspan="4">Quantity</th>
                        </tr>
                        <tr>
                            <th>A</th>
                            <th>B</th>
                            <th>O</th>
                            <th>AB</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockOutList as $key => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $key }}</td>
                                <td>{{ $item['RequestDate'] }}</td>
                                <td>{{ $item['location'] }}</td>
                                <td>{{ $item['ShipDate'] }}</td>
                                <td>{{ $item['Status'] }}</td>
                                <td>{{ $item['Quantity']['BloodTypeA'] }}</td>
                                <td>{{ $item['Quantity']['BloodTypeB'] }}</td>
                                <td>{{ $item['Quantity']['BloodTypeO'] }}</td>
                                <td>{{ $item['Quantity']['BloodTypeAB'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h4 style="color: #dc3545; text-decoration: underline;">Summary</h4>
                {{-- TOTAL NUMBER OF BLOOD RECEIVE --}}
                <h5 class="text-dark text-decoration-underline">Total Number of Blood Shipped</h5>
                <div class="row">
                    <div class="row">
                        <!-- VIEW BLOOD - A - TOTAL NUMBER OF RECEIVE -->
                        <div class="column">
                            <!-- TITLE -->
                            <div class="row">
                                <span
                                    style="text-decoration: underline; color: #dc3545; font-weight: bold; text-align: center;">
                                    Blood Type A
                                </span>
                            </div>
                            <!-- QUANTITY -->
                            <div class="row">
                                <span style="color: #343a40; font-weight: bold;"> Positive:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_Shipped['aPositive'] }}
                                    </span>
                                </span>
                                <span style="color: #343a40; font-weight: bold;"> Negative:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_Shipped['aNegative'] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                        <!-- VIEW BLOOD - B - TOTAL NUMBER OF RECEIVE -->
                        <div class="column">
                            <div class="row">
                                <span
                                    style="text-decoration: underline; color: #dc3545; font-weight: bold; text-align: center;">
                                    Blood Type B
                                </span>
                            </div>
                            <div class="row">
                                <span style="color: #343a40; font-weight: bold;"> Positive:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_Shipped['bPositive'] }}
                                    </span>
                                </span>
                                <span style="color: #343a40; font-weight: bold;"> Negative:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_Shipped['bNegative'] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- VIEW BLOOD - O - TOTAL NUMBER OF RECEIVE -->
                        <div class="column">
                            <div class="row">
                                <span
                                    style="text-decoration: underline; color: #dc3545; font-weight: bold; text-align: center;">
                                    Blood Type O
                                </span>
                            </div>
                            <div class="row">
                                <span style="color: #343a40; font-weight: bold;"> Positive:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_Shipped['oPositive'] }}
                                    </span>
                                </span>
                                <span style="color: #343a40; font-weight: bold;"> Negative:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_Shipped['oNegative'] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                        <!-- VIEW BLOOD - AB - TOTAL NUMBER OF RECEIVE -->
                        <div class="column">
                            <div class="row">
                                <span
                                    style="text-decoration: underline; color: #dc3545; font-weight: bold; text-align: center;">
                                    Blood Type AB
                                </span>
                            </div>
                            <div class="row">
                                <span style="color: #343a40; font-weight: bold;"> Positive:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_Shipped['abPositive'] }}
                                    </span>
                                </span>
                                <span style="color: #343a40; font-weight: bold;"> Negative:
                                    <span style="color: #343a40; font-weight: normal;">
                                        {{ $numOfBlood_Shipped['abNegative'] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
