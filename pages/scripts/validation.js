

$(document).ready(function () {
    // Toggle password visibility
    $('#togglePassword').on('click', function () {
        const $password = $('#loginPassword');
        const $icon = $('#eyeIcon');
        const type = $password.attr('type') === 'password' ? 'text' : 'password';
        $password.attr('type', type);

        // Toggle between eye and eye-slashn (TO BE WORKED ON)
        if (type === 'text') {
            $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Switch to Register modal
    $('#loginTextLink').on('click', function (e) {
        e.preventDefault();
        $('#loginModal').modal('hide');
        setTimeout(function () {
            $('#registerModal').modal('show');
        }, 400); // Wait for login modal to close
    });

    // Register modal password eye icon
    $('#toggleRegisterPassword').on('click', function () {
        var $password = $('#registerPassword');
        var type = $password.attr('type') === 'password' ? 'text' : 'password';
        $password.attr('type', type);
        var $icon = $('#registerEyeIcon');
        if (type === 'text') {
            $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    $('#toggleRegisterRetypePassword').on('click', function () {
        var $password = $('#registerRetypePassword');
        var type = $password.attr('type') === 'password' ? 'text' : 'password';
        $password.attr('type', type);
        var $icon = $('#registerRetypeEyeIcon');
        if (type === 'text') {
            $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Email validation (same as contact.html)
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    $('#loginForm').on('submit', function (e) {
        var email = $('#loginEmail').val().trim();
        var password = $('#loginPassword').val();
        var valid = true;
        $('#loginFeedback').hide();
        $('#loginEmail, #loginPassword').removeClass('is-invalid');

        if (!isValidEmail(email)) {
            $('#loginEmail').addClass('is-invalid');
            $('#loginFeedback').text('Please enter a valid email.').css('color', 'red').show();
            valid = false;
        }
        if (password.length < 6) {
            $('#loginPassword').addClass('is-invalid');
            $('#loginFeedback').text('Password must be at least 6 characters.').css('color', 'red').show();
            valid = false;
        }
        if (!valid) {
            e.preventDefault();
            return;
        }
        // Simulate login success
        $('#loginFeedback').text('Login successful!').css('color', 'green').show();
        e.preventDefault();
        setTimeout(function () {
            $('#loginModal').modal('hide');
            $('#loginForm')[0].reset();
            $('#loginFeedback').hide();
        }, 1200);
    });

    // Remove invalid class on input
    $('#loginEmail, #loginPassword').on('input', function () {
        $(this).removeClass('is-invalid');
        $('#loginFeedback').hide();
    });

    // Register form validation
    function isValidNameSpaces(str) {
        // Allows multiple words, only single spaces between, no leading/trailing spaces
        return /^([A-Za-z]+)( [A-Za-z]+)*$/.test(str.trim());
    }
    function isValidNoSpace(str) {
        // No spaces allowed
        return /^[A-Za-z]+$/.test(str.trim());
    }
    function isValidAddress(str) {
        // Allows multiple words, only single spaces between, no leading/trailing spaces
        return /^([A-Za-z0-9.,#\-]+)( [A-Za-z0-9.,#\-]+)*$/.test(str.trim());
    }
    function isValidContact(str) {
        // Must be exactly 10 digits
        return /^\d{10}$/.test(str);
    }

    $('#registerFormServer, #registerForm').on('submit', function (e) {
        var valid = true;
        var firstName = $('#registerFirstName').val().trim();
        var lastName = $('#registerLastName').val().trim();
        var address = $('#registerAddress').val().trim();
        var email = $('#registerEmail').val().trim();
        var contact = $('#registerContact').val().trim();
        var password = $('#registerPassword').val();
        var retype = $('#registerRetypePassword').val();
        $('#registerFeedback').hide();
        $('#registerFormServer .form-control, #registerForm .form-control').removeClass('is-invalid');

        // First Name
        if (!isValidNameSpaces(firstName)) {
            $('#registerFirstName').addClass('is-invalid');
            $('#registerFeedback').text('First name: Only letters, single spaces between words, no leading/trailing spaces.').css('color', 'red').show();
            valid = false;
        }
        // Last Name
        if (!isValidNoSpace(lastName)) {
            $('#registerLastName').addClass('is-invalid');
            $('#registerFeedback').text('Last name: Only letters, no spaces allowed.').css('color', 'red').show();
            valid = false;
        }
        // Address
        if (!isValidAddress(address)) {
            $('#registerAddress').addClass('is-invalid');
            $('#registerFeedback').text('Address: Only single spaces between words, no leading/trailing spaces.').css('color', 'red').show();
            valid = false;
        }
        // Email
        if (!isValidEmail(email)) {
            $('#registerEmail').addClass('is-invalid');
            $('#registerFeedback').text('Please enter a valid email.').css('color', 'red').show();
            valid = false;
        }
        // Contact
        if (!isValidContact(contact)) {
            $('#registerContact').addClass('is-invalid');
            $('#registerFeedback').text('Contact: Must be exactly 10 digits after +63.').css('color', 'red').show();
            valid = false;
        }
        // Password
        if (password.length < 6) {
            $('#registerPassword').addClass('is-invalid');
            $('#registerFeedback').text('Password must be at least 6 characters.').css('color', 'red').show();
            valid = false;
        }
        // Retype Password
        if (retype !== password || retype.length < 6) {
            $('#registerRetypePassword').addClass('is-invalid');
            $('#registerFeedback').text('Retype password must match password.').css('color', 'red').show();
            valid = false;
        }
        if (!valid) {
            e.preventDefault();
            return;
        }
        // If valid, allow the form to submit to server/register.php (no preventDefault)
    });

    // Remove invalid class on input for register
    $('#registerFormServer .form-control, #registerForm .form-control').on('input', function () {
        $(this).removeClass('is-invalid');
        $('#registerFeedback').hide();
    });
});