function isValid(value){
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

// Debounce function to delay execution
function debounce(func, delay) {
    let debounceTimer;
    return function (...args) {
        const context = this;
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => func.apply(context, args), delay);
    };
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
});

document.querySelectorAll(".quant").forEach((quantityInput) => {
    quantityInput.oninput = debounce(function () {
        // Get the currently active input's value
        let quant = this.value;

        if (isValid(parseInt(quant))) {
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
                    console.log("Server says:", response);
                },
                error: function (error) {
                    console.error('Error updating quantity:', error);
                }
            });
        }
    }, 750); // 750ms delay before sending the request
});

