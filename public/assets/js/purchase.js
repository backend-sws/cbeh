listenKeyup(
    ".purchase-quantity,.purchase-price,purchase-quantity,.purchase-tax,.purchase-discount",
    function () {
        let value = $(this).val();
        $(this).val(value.replace(/[^0-9\.]/g, ""));
        var currentRow = $(this).closest("tr");
        let currentqty = currentRow.find(".purchase-quantity").val();
        let price = currentRow.find(".purchase-price").val();
        let currentamount = parseFloat(price * currentqty);
        currentRow.find(".purchase-amount").val(currentamount.toFixed(2));

        let taxEle = $(".purchase-tax");
        let elements = $(".purchase-amount");
        let total = 0.0;
        let totalTax = 0;
        let netAmount = 0;
        let discount = 0;
        let amount = 0;
        for (let i = 0; i < elements.length; i++) {
            total += parseFloat(elements[i].value);
            discount = $(".purchase-discount").val();
            if (taxEle[i].value != 0 && taxEle[i].value != "") {
                if (taxEle[i].value > 99) {
                    let taxAmount = taxEle[i].value.slice(0, -1);
                    currentRow.find(".purchase-tax").val(taxAmount);
                    displayErrorMessage(Lang.get("js.validate_tax"));
                    $("#discountAmount").val(discount);
                    return false;
                }
                totalTax += (elements[i].value * taxEle[i].value) / 100;
            } else {
                amount += parseFloat(elements[i].value);
            }
        }
        discount = discount == "" ? 0 : discount;
        netAmount = parseFloat(total) + parseFloat(totalTax);
        netAmount = parseFloat(netAmount) - parseFloat(discount);
        if (discount > total && $(this).hasClass("purchase-discount")) {
            discount = discount.slice(0, -1);
            displayErrorMessage(Lang.get("js.validate_discount"));
            $("#discountAmount").val(discount);
            return false;
        }
        if (discount > total) {
            netAmount = 0;
        }

        $("#total").val(total.toFixed(2));
        $("#purchaseTaxId").val(totalTax.toFixed(2));
        $("#netAmount").val(netAmount.toFixed(2));
    }
);