document.addEventListener('DOMContentLoaded', function () {
    // Get login button
    const btn = document.getElementById('open-login');

    // Create popup
    const popup = document.createElement('div');
    popup.id = 'login-popup';
    popup.innerHTML = `
        <div class="rk-popup-overlay" id="rk-login-overlay"></div>
        <div class="rk-popup-panel" role="dialog" aria-modal="true" aria-label="Inloggen">
            <div class="rk-popup-header">
                <span id="login-title">Inloggen</span>
                <button class="rk-popup-close" id="rk-login-close" aria-label="Sluiten">&times;</button>
            </div>
            <div class="rk-popup-body" id="login-body">

                <!-- Login form -->
                <div id="login-form">
                    <div class="rk-field">
                        <label>E-mailadres</label>
                        <input type="email" id="login-email" placeholder="uw@email.nl">
                    </div>
                    <div class="rk-field">
                        <label>Wachtwoord</label>
                        <input type="password" id="login-password" placeholder="••••••••">
                    </div>
                    <p class="rk-error" id="login-error"></p>
                    <button class="rk-submit-btn" id="login-submit">Inloggen</button>
                    <p class="rk-switch">Nog geen account? <a href="#" id="go-register">Registreren</a></p>
                </div>

                <!-- Register form -->
                <div id="register-form" style="display:none;">
                    <div class="rk-field">
                        <label>Naam</label>
                        <input type="text" id="reg-naam" placeholder="Uw naam">
                    </div>
                    <div class="rk-field">
                        <label>E-mailadres</label>
                        <input type="email" id="reg-email" placeholder="uw@email.nl">
                    </div>
                    <div class="rk-field">
                        <label>Wachtwoord</label>
                        <input type="password" id="reg-password" placeholder="••••••••">
                    </div>
                    <p class="rk-error" id="reg-error"></p>
                    <button class="rk-submit-btn" id="reg-submit">Registreren</button>
                    <p class="rk-switch">Al een account? <a href="#" id="go-login">Inloggen</a></p>
                </div>

                <!-- Logged in -->
                <div id="logged-in-form" style="display:none;">
                    <p id="welcome-msg"></p>
                    <button class="rk-submit-btn" id="logout-btn">Uitloggen</button>
                </div>

            </div>
        </div>
    `;
    document.body.appendChild(popup);

    // Open popup
    btn.addEventListener('click', function () {
        popup.classList.add('rk-popup-active');
        document.body.style.overflow = 'hidden';
        checkSession();
    });

    // Close via X
    document.getElementById('rk-login-close').addEventListener('click', closePopup);

    // Close via overlay
    document.getElementById('rk-login-overlay').addEventListener('click', closePopup);

    // Close via Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closePopup();
    });

    function closePopup() {
        popup.classList.remove('rk-popup-active');
        document.body.style.overflow = '';
    }

    // Switch to register form
    document.getElementById('go-register').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('register-form').style.display = 'block';
        document.getElementById('login-title').textContent = 'Registreren';
    });

    // Switch to login form
    document.getElementById('go-login').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('register-form').style.display = 'none';
        document.getElementById('login-form').style.display = 'block';
        document.getElementById('login-title').textContent = 'Inloggen';
    });

    // Login submit
    document.getElementById('login-submit').addEventListener('click', function () {
        const email = document.getElementById('login-email').value;
        const wachtwoord = document.getElementById('login-password').value;

        fetch('/De-Romeinse-Kade/Lucas/pages/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'login', email, wachtwoord })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                showLoggedIn(res.naam);
            } else {
                document.getElementById('login-error').textContent = res.error;
            }
        });
    });

    // Register submit
    document.getElementById('reg-submit').addEventListener('click', function () {
        const naam = document.getElementById('reg-naam').value;
        const email = document.getElementById('reg-email').value;
        const wachtwoord = document.getElementById('reg-password').value;

        fetch('/De-Romeinse-Kade/Lucas/pages/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'register', naam, email, wachtwoord })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                showLoggedIn(res.naam);
            } else {
                document.getElementById('reg-error').textContent = res.error;
            }
        });
    });

    // Logout
    document.getElementById('logout-btn').addEventListener('click', function () {
        fetch('/De-Romeinse-Kade/Lucas/pages/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'logout' })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                document.getElementById('logged-in-form').style.display = 'none';
                document.getElementById('login-form').style.display = 'block';
                document.getElementById('login-title').textContent = 'Inloggen';
            }
        });
    });

    // Check if already logged in via session
    function checkSession() {
        fetch('/De-Romeinse-Kade/Lucas/pages/session_check.php')
            .then(r => r.json())
            .then(res => {
                if (res.loggedin) {
                    showLoggedIn(res.naam);
                }
            });
    }

    // Show logged in state
    function showLoggedIn(naam) {
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('register-form').style.display = 'none';
        document.getElementById('logged-in-form').style.display = 'block';
        document.getElementById('login-title').textContent = 'Mijn account';
        document.getElementById('welcome-msg').textContent = 'Welkom, ' + naam + '!';
    }
});