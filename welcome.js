$(document).ready(function () {
  $("#invoice-inside").DataTable({
    lengthChange: false,
    pageLength: 5,

    columnDefs: [
      { className: "dt-center", targets: "_all" },
      { orderable: false, targets: [4, 5, 6, 7, 8, 9] },
    ],
  });
});

function removedetails(ele) {
  let id = Number(ele.getAttribute("id"));
  let inv_id = document.getElementById(`row${id}`);
  // console.log(inv_id.innerText);
  if (confirm("Do you really want to delete this invoice?")) {
    let urll = "/DBMS_Project/remove_invoice.php?inv_id=" + inv_id.innerText;
    window.location.href = urll;
  }
}
