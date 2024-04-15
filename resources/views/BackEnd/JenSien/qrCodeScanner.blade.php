@extends('BackEnd.app')
@section('content')

<div id="scanner-container"></div>
<div id="result"></div>

<script src="https://cdn.jsdelivr.net/npm/quagga"></script>
<script>
    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector("#scanner-container"),
            constraints: {
                facingMode: "environment" // Use rear camera (if available)
            }
        },
        decoder: {
            readers: ["code_128_reader"] // Specify the type of codes to scan (e.g., code 128)
        }
    }, function(err) {
        if (err) {
            console.error("Failed to initialize Quagga: ", err);
            return;
        }
        console.log("Quagga initialized successfully");
        Quagga.start();
    });

    Quagga.onDetected(function(result) {
        console.log("Scanned code:", result.codeResult.code);
        document.getElementById("result").innerText = "QR Code: " + result.codeResult.code;
        // You can also redirect or perform any action based on the scanned QR code
    });
</script>
@endsection