/* ==========================================================================
   PAGE CUSTOM JAVASCRIPT
   ==========================================================================*/

/* --------------------------------------------------------------------------
   jQuery Validation Settings
   --------------------------------------------------------------------------
   Configures form validation settings such as error elements, highlighting, 
   and unhighlighting input fields.
-------------------------------------------------------------------------- */
jQuery.validator.setDefaults({
    errorElement: 'div',
    errorClass: 'invalid-feedback',
    highlight: function (element) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element) {
        $(element).removeClass('is-invalid');
    }
});

/* --------------------------------------------------------------------------
   Toggle Password Visibility
   --------------------------------------------------------------------------
   Toggles between showing and hiding the password input field.
-------------------------------------------------------------------------- */
$('#show-password-icon').click(function () {
    let passwordInput = $('#password');
    let icon = $(this);

    let isPassword = passwordInput.attr('type') === 'password';
    passwordInput.attr('type', isPassword ? 'text' : 'password');
    icon.toggleClass('fa-eye fa-eye-slash');
});

$('#copyReferral').click(function () {
    let referralLink = $('#referralLink').val();

    if (!referralLink) {
        window.location.href = "{{ route('login') }}";
        return;
    }

    navigator.clipboard.writeText(referralLink).then(() => {
        Swal.fire('Success!', 'Referral link copied!', 'success');
    }).catch(err => {
        console.error("Failed to copy:", err);
    });
});

/* --------------------------------------------------------------------------
   Cookie Management Functions
   --------------------------------------------------------------------------
   Functions to get and set cookies in the browser.
-------------------------------------------------------------------------- */
function getCookie(name) {
    let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? match[2] : null;
}

function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + "; path=/" + expires;
}

/* --------------------------------------------------------------------------
   Print Specific Div Content
   --------------------------------------------------------------------------
   Prints the content of a specified div.
-------------------------------------------------------------------------- */
function printDiv(divId) {
    let divContents = $('#' + divId).html();
    let originalContents = $('body').html();

    $('body').html(divContents);
    window.print();
    $('body').html(originalContents);
}

// Top Margin for sticky
function updateTopPosition() {
    var headerHeight = $(".page-header").height();
    $(".sticky-top-header").css("top", headerHeight + "px");
}

/* --------------------------------------------------------------------------
   Dark Mode / Light Mode Toggle
   --------------------------------------------------------------------------
   Enables users to switch between light and dark themes.
   - Default theme is dark mode if none is set.
   - Saves the theme selection in localStorage.
   - Updates the theme, icon, and logo dynamically based on user selection.
-------------------------------------------------------------------------- */
$(document).ready(function () {
    const themeToggle = $('#theme-toggle');
    const body = $('body');
    const logo = $('.logo');
    updateTopPosition();

    // Set default theme to dark if no theme is saved in localStorage
    if (localStorage.getItem('theme') === null) {
        localStorage.setItem('theme', 'dark');
    }

    // Apply the theme from localStorage
    function applyTheme(theme) {
        if (theme === 'dark') {
            body.removeClass('light-mode').addClass('dark-mode');
            logo.attr('src', "/assets/images/logos/dark.png"); // Set dark mode logo
            themeToggle.html('<i class="fas fa-sun"></i>'); // Sun icon for light mode
        } else {
            body.removeClass('dark-mode').addClass('light-mode');
            logo.attr('src', "/assets/images/logos/light.png"); // Set light mode logo
            themeToggle.html('<i class="fas fa-moon"></i>'); // Moon icon for dark mode
        }
    }

    applyTheme(localStorage.getItem('theme'));

    // Toggle theme on button click
    themeToggle.on('click', function () {
        let newTheme = body.hasClass('dark-mode') ? 'light' : 'dark';
        localStorage.setItem('theme', newTheme);
        applyTheme(newTheme);
    });
});

$(window).resize(updateTopPosition);
