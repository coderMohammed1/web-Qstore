function isVaild(value){
    try{
        if(!(typeof value === 'number') || value <= 0 || value == ""){
            throw new Error("Invalid value!");
        }
        console.log("Passed value is valid");
        return true;
    }
    catch(error){
        return false;
        console.log("An error occurred: ", error.message);
    }
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
});

document.querySelectorAll(".quant").forEach((quantityInput) => {
    quantityInput.oninput = function () {
        // Get the currently active input's value
        let quant = this.value;

        if(isVaild(parseInt(quant))){
        // Get the sibling hidden input's value for the product ID
            let pid = this.parentNode.querySelector('input[name="pid"]').value;

            // Send the AJAX request
            $.ajax({
                url: '/cart/quantity',
                type: 'POST',
                contentType: 'application/x-www-form-urlencoded',
                data: {
                    number: quant,
                    prod: pid
                },
                success: function (response) {
                    console.log("server says:", response);
                },
                error: function (error) {
                    console.error('Error updating quantity:', "error");
                }
            });
        }
    }
});

