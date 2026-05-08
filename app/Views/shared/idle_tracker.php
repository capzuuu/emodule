<script nonce="<?= csp_nonce() ?>">
    (function() {
        const TIMEOUT = 600;
        const WARN_AT = 60;
        const HEARTBEAT = 60;
        const LOGOUT_URL = '<?= baseurl('/auth/logout') ?>';
        const PING_URL = '<?= baseurl('/session/heartbeat') ?>';

        let idleSeconds = 0;
        let warned = false;
        let warningTimer = null;

        ['mousemove', 'mousedown', 'keydown', 'touchstart', 'scroll', 'click'].forEach(function(evt) {
            document.addEventListener(evt, resetIdle, { passive: true });
        });

        function resetIdle() {
            idleSeconds = 0;
            if (warned) {
                warned = false;
                clearTimeout(warningTimer);
                const modal = document.getElementById('idleWarningModal');
                if (modal && window.$) $(modal).modal('hide');
            }
        }

        setInterval(function() {
            idleSeconds++;
            if (!warned && idleSeconds >= (TIMEOUT - WARN_AT)) {
                warned = true;
                showWarning();
            }
            if (idleSeconds >= TIMEOUT) {
                window.location.href = LOGOUT_URL;
            }
        }, 1000);

        setInterval(function() {
            if (idleSeconds < (TIMEOUT - WARN_AT) && window.$) {
                $.post(PING_URL);
            }
        }, HEARTBEAT * 1000);

        function showWarning() {
            let modal = document.getElementById('idleWarningModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'idleWarningModal';
                modal.className = 'modal fade';
                modal.setAttribute('tabindex', '-1');
                modal.setAttribute('data-backdrop', 'static');
                modal.setAttribute('data-keyboard', 'false');
                modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-header bg-warning">
                            <h5 class="modal-title"><i class="bi bi-clock-fill me-1"></i> Session Expiring</h5>
                        </div>
                        <div class="modal-body text-center">
                            <p class="mb-1">You've been inactive. You will be logged out in</p>
                            <h3 id="idleCountdown" class="text-danger font-weight-bold">${WARN_AT}</h3>
                            <p class="mb-0">seconds.</p>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-primary" id="idleStayBtn">
                                <i class="bi bi-check-lg me-1"></i> Stay Logged In
                            </button>
                        </div>
                    </div>
                </div>`;
                document.body.appendChild(modal);
                document.getElementById('idleStayBtn').addEventListener('click', function() {
                    resetIdle();
                    if (window.$) $.post(PING_URL);
                });
            }
            if (window.$) $(modal).modal('show');
            const countdownEl = document.getElementById('idleCountdown');
            warningTimer = setInterval(function() {
                const remaining = TIMEOUT - idleSeconds;
                if (countdownEl) countdownEl.textContent = remaining > 0 ? remaining : 0;
                if (remaining <= 0) clearInterval(warningTimer);
            }, 1000);
        }
    })();
</script>