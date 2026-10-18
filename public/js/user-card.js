(function () {
    /* Must match dashboard.css: shine ends at 16% of a 10s linear cycle. */
    var SHINE_MS = 10000;
    var SHINE_END_MS = Math.round(SHINE_MS * 0.12);

    function flipAvatar(host) {
        const front = host.getAttribute('data-avatar-front') || '';
        const back = host.getAttribute('data-avatar-back') || '';
        if (!front || !back) {
            return;
        }

        const img = host.querySelector('img');
        if (!img) {
            return;
        }

        const showingBack = host.getAttribute('data-avatar-side') === 'back';
        const nextUrl = showingBack ? front : back;
        host.setAttribute('data-avatar-side', showingBack ? 'front' : 'back');
        img.src = nextUrl;
        host.style.setProperty('--user-avatar-mask', "url('" + nextUrl + "')");
    }

    function hasBack(host) {
        return !!host.getAttribute('data-avatar-back');
    }

    function prefersReducedMotion() {
        return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    function isShineEvent(event) {
        const name = String(event.animationName || '');
        if (name && name !== 'user-avatar-shine') {
            return false;
        }
        const pseudo = String(event.pseudoElement || '');
        if (pseudo && pseudo !== '::after' && pseudo !== ':after') {
            return false;
        }
        return true;
    }

    function scheduleFlipAfterShine(host) {
        if (!hasBack(host)) {
            return;
        }
        if (host._avatarFlipTimer) {
            clearTimeout(host._avatarFlipTimer);
        }
        host._avatarFlipTimer = setTimeout(function () {
            host._avatarFlipTimer = null;
            flipAvatar(host);
        }, SHINE_END_MS);
    }

    function restartShine(host) {
        if (host._avatarFlipTimer) {
            clearTimeout(host._avatarFlipTimer);
            host._avatarFlipTimer = null;
        }
        host.classList.add('user-avatar-shine--restart');
        void host.offsetWidth;
        host.classList.remove('user-avatar-shine--restart');
    }

    function triggerHoverShine(host) {
        if (prefersReducedMotion()) {
            flipAvatar(host);
            return;
        }
        restartShine(host);
        scheduleFlipAfterShine(host);
    }

    function bind(host) {
        if (!host || host.getAttribute('data-avatar-shine-bound') === '1') {
            return;
        }

        host.setAttribute('data-avatar-shine-bound', '1');

        if (hasBack(host)) {
            host.setAttribute('data-avatar-side', host.getAttribute('data-avatar-side') || 'front');

            function onShineCycle(event) {
                if (!isShineEvent(event)) {
                    return;
                }
                scheduleFlipAfterShine(host);
            }

            host.addEventListener('animationstart', onShineCycle);
            host.addEventListener('animationiteration', onShineCycle);
        }

        host.addEventListener('mouseenter', function () {
            triggerHoverShine(host);
        });
    }

    function bindAll(root) {
        const scope = root && root.querySelectorAll ? root : document;
        scope.querySelectorAll('.user-avatar-shine').forEach(bind);
    }

    window.FfbAvatarShine = {
        bind: bind,
        bindAll: bindAll,
    };

    function init() {
        bindAll(document);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
