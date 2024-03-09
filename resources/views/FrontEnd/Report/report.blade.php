<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .table{
            width: 500px;
            border: 1px solid black;
            
            margin: auto;
            
        }
        table{
            margin: 5px;
            padding: 8px;
            margin: auto;
        }
        tr{
            min-height: 5px;
        }
        td{
            min-height: 10px;
            padding: 8px;
        }
    </style>
    <title>Blood Test Result</title>
</head>
<body>
    <div class="container">
        <h3>Blood Test Result</h3>
    </div>
    <hr>
    <div class="table">
        <table class="table table-bordered">
            <tr>
                <td>Name: {{$userName}}</td>
                <td>Date: {{$date}}</td>
            </tr>
            <tr>
                <td>IC: {{$ic}}</td>
            </tr>
            <tr>
                <td>Gender: {{$gender}}</td>
            </tr>
            <tr>
                <td>Hospital: {{$hospital}}</td>
            </tr>
            <tr style="height: 8px;"></tr>
            <tr>
                <td><b><u>Blood Test Result</u></b></td>
            </tr>
            <tr>
                <td>Blood Type: {{$bloodType}}</td>
            </tr>
            <tr>
                <td>Test Result: {{$result}}</td>
            </tr>
        </table>
    </div>
</body>
</html>