$(document).ready(function(){
    // registratio form
    $('#registerForm').submit(function(event) {
        event.preventDefault();

        let username = $('#username').val().trim();
        let email = $('#email').val().trim();
        let password = $('#password').val().trim();

        let usernameRegex = /^[a-zA-Z]{4,}$/; // Prvo slovo veliko ili malo,ostalo malo
        let emailRegex = /^[a-zA-Z][a-zA-Z0-9]*@[a-z]+\.[a-z]+$/; // samo mala slova pre i posle '@'
        let passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/; // Jedan broj, jedno slovo, jedan specijalan karatker, najmanje 6 karaktera

        let usernameError = $('#usernameError');
        let emailError = $('#emailError');
        let passwordError = $('#passwordError');

        usernameError.text('');
        emailError.text('');
        passwordError.text('');

        if (!usernameRegex.test(username)) {
            usernameError.text('Username must start with a letter and contain only lowercase letters.');
            return;
        }

        if (!emailRegex.test(email)) {
            emailError.text('Please enter a valid email address.');
            return;
        }

        if (!passwordRegex.test(password)) {
            passwordError.text('Password must contain at least one letter, one number, one special character, and be at least 6 characters long.');
            return;
        }
        
        window.location.href = "index.php";
        this.submit();
    });

    // contact admin velidation
    document.getElementById('contactForm').addEventListener('submit', function(event) {
        const subjectPattern = /^[a-zA-Z\s]+$/;
        const messagePattern = /^.{1,1000}$/;
    
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;
    
        if (!subjectPattern.test(subject)) {
            alert('Please enter a valid subject.');
            event.preventDefault();
            return false;
        }
    
        if (!messagePattern.test(message)) {
            alert('Please enter a valid message.');
            event.preventDefault();
            return false;
        }
    
        return true;
    });
});
