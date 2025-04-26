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
// Toggle password visibility
$('.toggle-password').click(function () {
    const passwordInput = $(this).parent().find('input');
    const icon = $(this).find('i');

    if (passwordInput.attr('type') === 'password') {
        passwordInput.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        passwordInput.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
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

// Function to create a dynamic escort statement
function createEscortStatement(escort) {
    // Gender-specific greetings
    const femaleGreetings = [
        "Meet the beautiful", "Introducing the lovely", "Say hello to the stunning",
        "Discover the charming", "Check out the gorgeous", "You'll love the delightful",
        "Welcome the elegant", "Here's the amazing", "Meet the fabulous", "Get to know the radiant"
    ];

    const maleGreetings = [
        "Meet the handsome", "Introducing the charming", "Say hello to the dashing",
        "Discover the charismatic", "Check out the striking", "You'll love the confident",
        "Welcome the impressive", "Here's the captivating", "Meet the suave", "Get to know the debonair"
    ];

    const neutralGreetings = [
        "Meet", "Introducing", "Say hello to", "Discover",
        "Check out", "You'll love", "Welcome", "Here's",
        "Meet the amazing", "Get to know"
    ];

    // Safely get gender in lowercase (fallback to empty string)
    const gender = escort?.gender ? escort.gender.toLowerCase() : '';

    // Select a random greeting based on gender
    let randomGreeting;
    if (gender.includes('female') || gender.includes('woman')) {
        randomGreeting = femaleGreetings[Math.floor(Math.random() * femaleGreetings.length)];
    } else if (gender.includes('male') || gender.includes('man')) {
        randomGreeting = maleGreetings[Math.floor(Math.random() * maleGreetings.length)];
    } else {
        randomGreeting = neutralGreetings[Math.floor(Math.random() * neutralGreetings.length)];
    }

    // Extract first name safely (fallback to empty string)
    const name = escort?.name ? escort.name.split(' ')[0] : 'Escort';

    // Calculate age safely
    const age = escort?.dob ? Math.round(calculateAge(escort.dob)) : null;

    // Fetch other details with safe fallbacks
    const nationality = escort?.country || 'Kenya';
    const city = escort?.city || '';
    const area = escort?.area || '';

    // Build location string properly
    let location = '';
    if (area && city) {
        location = `${area} in ${city}`;
    } else if (city) {
        location = city;
    } else if (area) {
        location = area;
    }

    // Build gender label properly
    const genderLabel = escort?.gender ? `<b>${escort.gender}</b>` : 'escort';

    // Final sentence construction
    return `${randomGreeting} <b>${name}</b>, ${age ? `a <b>${age}</b> year old` : ''} ${nationality} ${genderLabel} ${location ? `from <b>${location}</b>` : ''}.`;
}

// Helper function to calculate age from date of birth
function calculateAge(dob) {
    const birthDate = new Date(dob);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDifference = today.getMonth() - birthDate.getMonth();

    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
}
