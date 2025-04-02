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

function createEscortStatement(escort) {
    // Gender-specific greetings
    const femaleGreetings = [
        "Meet the beautiful",
        "Introducing the lovely",
        "Say hello to the stunning",
        "Discover the charming",
        "Check out the gorgeous",
        "You'll love the delightful",
        "Welcome the elegant",
        "Here's the amazing",
        "Meet the fabulous",
        "Get to know the radiant"
    ];

    const maleGreetings = [
        "Meet the handsome",
        "Introducing the charming",
        "Say hello to the dashing",
        "Discover the charismatic",
        "Check out the striking",
        "You'll love the confident",
        "Welcome the impressive",
        "Here's the captivating",
        "Meet the suave",
        "Get to know the debonair"
    ];

    const neutralGreetings = [
        "Meet",
        "Introducing",
        "Say hello to",
        "Discover",
        "Check out",
        "You'll love",
        "Welcome",
        "Here's",
        "Meet the amazing",
        "Get to know"
    ];

    // Select greeting based on gender
    let randomGreeting;
    const gender = escort.gender.toLowerCase();

    if (gender.includes('female') || gender.includes('woman')) {
        randomGreeting = femaleGreetings[Math.floor(Math.random() * femaleGreetings.length)];
    } else if (gender.includes('male') || gender.includes('man')) {
        randomGreeting = maleGreetings[Math.floor(Math.random() * maleGreetings.length)];
    } else {
        randomGreeting = neutralGreetings[Math.floor(Math.random() * neutralGreetings.length)];
    }

    const name = escort.name.split(' ')[0];  // Extract first name
    const age = Math.round(calculateAge(escort.dob)) ?? 0;
    const nationality = escort.country;
    const city = escort.city;
    const area = escort.area || '';

    return `${randomGreeting} <b>${name}</b>, a <b>${age}</b> year old Kenyan <b>${escort.gender}</b> escort from <b>${area}</b> in <b>${city}</b>, <b>${nationality}</b>.`;
}

// Helper function to calculate age based on date of birth (dob)
function calculateAge(dob) {
    const birthDate = new Date(dob);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const month = today.getMonth();
    const day = today.getDate();
    if (month < birthDate.getMonth() || (month === birthDate.getMonth() && day < birthDate.getDate())) {
        age--;
    }
    return age;
}
