<button id="pay-button">Bayar Sekarang</button>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
document.getElementById('pay-button').onclick = function () {
    snap.pay("{{ $snapToken }}");
};
</script>