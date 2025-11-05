

// Custom name validation rule
$.validator.addMethod("validName", function (value, element) {

    // Trim check
    if (value !== value.trim()) {
        return false;
    }

    // Regex validation
    return /^([A-Za-z]+\.?)( [A-Za-z]+\.?)*$/.test(value);

}, "The name must only contain letters.");

console.log('validator');

$(function () {

    $("#contactForm").validate({

        rules: {
            name: {
                required: true,
                validName: true
            },
            email: {
                required: true,
                email: true // safe built-in rule
            },
            message: {
                required: true
            }
        },

        messages: {
            name: {
                required: "Name is required.",
                validName: "The name must only contain letters."
            },
            email: {
                required: "Email is required.",
                email: "Please enter a valid email address."
            },
            message: {
                required: "Please enter your message."
            }
        },

        // Bootstrap error classes
        errorClass: "is-invalid",
        validClass: "is-valid",

        // Where error text goes
        errorPlacement: function (error, element) {
            $("#formFeedback")
                .css("color", "red")
                .text(error.text())
                .show();
        },

        // Remove error styling on valid
        success: function () {
            $("#formFeedback").hide();
        },

        // Optional: prevent submission on failure (plugin does this automatically)
    });

    // Trim name on blur (kept from original)
    $("#contactName").on("blur", function () {
        var v = $(this).val();
        var t = v.trim();
        if (v !== t) {
            $(this).val(t).trigger("input");
        }
    });

    // Show modal on redirect after server PRG ( ?sent=1 )
    if (window.location.search.indexOf("sent=1") !== -1) {
        var modalEl = document.getElementById("successModal");
        if (modalEl) new bootstrap.Modal(modalEl).show();
        if (window.history && window.history.replaceState) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }

});