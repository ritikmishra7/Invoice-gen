const add = document.getElementById("addrow");
const item_table = document.getElementById("items-table");

// GET the last row id;

let current_rows = 5;
const rowsss = $("[id^=row]");
let lastele_id = rowsss[rowsss.length - 1].id;
current_rows = Number(lastele_id.slice(-1));

add.addEventListener("click", function () {
  current_rows++;
  const new_row = document.createElement("tr");
  new_row.setAttribute("class", `row${current_rows}`);
  new_row.setAttribute("id", `row${current_rows}`);
  new_row.innerHTML = `<td><button class="removerow" id="remove${current_rows}" type="button" onclick="removeparent(this)"><i class="fa-solid fa-trash"></i></button></td><td><input type="text" name="itemname[]" class="item-name${current_rows}" placeholder="Item Name"></td>
<td style="width: 40%;"><input type="text" name="itemdesc[]" class="item-desc${current_rows}" placeholder="Item Description here"></td>
<td ><input type="text" placeholder="0" id="rate${current_rows}" class="rate${current_rows}" value="" name="rate[]"></td>
<td id="qty"><input type="text" name="qty[]" placeholder="0" id="qty${current_rows}" class="qty${current_rows}"></td>
<td class="amt"><input type="text" placeholder="₹ 0" id="amt${current_rows}" class="amt${current_rows}" name="amount[]" readonly></td>`;

  item_table.appendChild(new_row);
});

function removeparent(ele) {
  if (confirm("Are you sure you want to delete this item?")) {
    ele.parentNode.parentNode.remove();
  }
  calculateTotal();
}

$(document).on("keyup", "[id^=rate]", function () {
  calculateTotal();
});

$(document).on("keyup", "[id^=qty]", function () {
  calculateTotal();
});

$(document).on("keyup", "[id=discount]", function () {
  calculateTotal();
});

function calculateTotal() {
  // let FinalAmount = 0;
  let subtotalamt = 0;

  //updating amount after rate and qty
  $("[id^=rate").each(function () {
    let id = $(this).attr("id");
    id = String(id).slice(-1);
    //bas row number nikla
    let rate = $("#rate" + id).val();
    let qty = $("#qty" + id).val();
    if (!qty) qty = 1;

    let total = Number(rate) * Number(qty);
    subtotalamt += total;
    $("#amt" + id).val(parseFloat(total));
  });

  //Updating subtotal values here
  $("#subtotal").val(parseFloat(subtotalamt).toFixed(2));

  //Updating tax-slab based on sub-total
  let taxx = fixTax();

  //Updating total and discount
  fixTotal(taxx);
}

function fixTax() {
  let sub_amt = $("#subtotal").val();
  let tax = 0;
  if (Number(sub_amt) < 1000) tax = 5;
  else if (Number(sub_amt) >= 1000 && Number(sub_amt) < 5000) tax = 10;
  else if (Number(sub_amt) >= 5000 && Number(sub_amt) < 15000) tax = 15;
  else tax = 18;

  $("#taxtotal").val(tax + " %");
  return tax;
}

//sets total and discount
function fixTotal(taxx) {
  let sub_amntt = $("#subtotal").val();
  let total_tax = (Number(taxx) * Number(sub_amntt)) / 100;
  let total_amntt = Number(sub_amntt) + Number(total_tax);

  let dis = $("#discount").val();
  // console.log(dis);
  if (!dis) dis = Number(0);
  if (Number(dis) != 0) {
    var discount_amntt = (Number(total_amntt) * Number(dis)) / 100;
    total_amntt = Number(total_amntt) - Number(discount_amntt);
  }

  $("#total").val(parseFloat(total_amntt).toFixed(2));
}
