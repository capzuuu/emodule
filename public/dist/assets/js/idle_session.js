/**
 * Simple Idle Session Script
 * Keeps session alive while user is active.
 * Logs out when inactive.
 */

(function () {

    // ===== CONFIG =====
const TIMEOUT  = window.IDLE_TIMEOUT || 300000; // 5 minutes
const WARNING  = 60000; // warn 1 minute before logout
    const LOGOUT   = window.LOGOUT_URL || "/auth/logout";

    let timer;
    let warned = false;

    function startTimer() {

        clearTimeout(timer);

        // show warning before logout
        if (!warned) {
            setTimeout(function () {
                warned = true;
                alert("You will be logged out due to inactivity.");
            }, TIMEOUT - WARNING);
        }

        // final logout
        timer = setTimeout(function () {
            window.location.href = LOGOUT;
        }, TIMEOUT);
    }

    function resetTimer() {
        warned = false;
        startTimer();
    }

    // detect user actions
    ["click", "mousemove", "keypress", "scroll", "touchstart"]
        .forEach(event => document.addEventListener(event, resetTimer, true));

    // start when page loads
    startTimer();

})();
