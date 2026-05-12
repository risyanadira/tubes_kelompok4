<!DOCTYPE html>
<html>
<head>

    <title>Pembayaran Midtrans</title>

    <script
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.clientKey') }}">
    </script>

</head>

<body>

    <script>

        window.onload = function () {

            snap.pay('{{ $snapToken }}', {

                onSuccess: function(result){

                    // redirect setelah sukses
                    window.location.href =
                        "/pembayaran-success/{{ $penjualan->id }}";

                },

                onPending: function(result){

                    alert('Menunggu pembayaran');

                    window.location.href = "/penjualan";

                },

                onError: function(result){

                    alert('Pembayaran gagal');

                    window.location.href = "/penjualan";

                }

            });

        };

    </script>

</body>
</html>