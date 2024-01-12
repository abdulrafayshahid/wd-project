"use strict";

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$(document).ready(function () {
  sessionStorage.setItem("booking_id", '');
});

function onScanSuccess(qrCodeMessage) {

  //
  $.ajax({
    type: 'POST',
    url: url,
    data: {
      booking_id: qrCodeMessage
    },
    success: function (data) {
      if (data == 'success') {
        swal({
          title: "Verified!",
          icon: "success",
        });
      } else {
        swal({
          title: "Unverified!",
          icon: "error",
        });
      }
    }
  });
}

function onScanError(errorMessage) {
  //handle scan error
}
var html5QrcodeScanner = new Html5QrcodeScanner(
  "reader", {
  fps: 10,
  qrbox: 250
});
html5QrcodeScanner.render(onScanSuccess, onScanError);
