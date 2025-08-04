<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
$URL = '';
$DefaultTitle = 'VoyagersBeat EMS';
$DefaultDesc = 'VoyagersBeat EMS';
?>

<!-- HTML Meta Tags -->
<title><?= htmlspecialchars($title ?? $DefaultTitle) ?></title>
<meta name="description" content="<?= (isset($description) && $description != "") ? $description : $DefaultDesc ?>">

<!-- Facebook Meta Tags -->
<meta property="og:url" content="<?= base_url() ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= (isset($title) && $title != "") ? $title : $DefaultTitle ?>">
<meta property="og:description" content="<?= (isset($description) && $description != "") ? $description : $DefaultDesc ?>">
<meta property="og:image" content="<?= (isset($image) && $image != "") ? $image : '/assets/images/screenshot.webp' ?>">

<!-- Twitter Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta property="twitter:domain" content="<?= base_url() ?>">
<meta property="twitter:url" content="<?= base_url() ?>">
<meta name="twitter:title" content="<?= (isset($title) && $title != "") ? $title : $DefaultTitle ?>">
<meta name="twitter:description" content="<?= (isset($description) && $description != "") ? $description : $DefaultDesc ?>">
<meta name="twitter:image" content="<?= (isset($image) && $image != "") ? $image : '/assets/images/screenshot.webp' ?>">

<!-- Meta Tags Generated via https://www.opengraph.xyz -->

<link rel="canonical" href="<?= (isset($canonical) && $canonical != "") ? base_url() . $canonical : base_url() ?>" />

<meta name="robots" content="<?= (isset($noindex) && $noindex != "") ? 'noindex, nofollow' : 'index, follow' ?>">

<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />

<link rel="manifest" href="/manifest.json">

<script>
    /* Partytown 0.7.5 - MIT builder.io */ ! function(t, e, n, i, r, o, a, d, s, c, p, l) {
        function u() {
            l || (l = 1, "/" == (a = (o.lib || "/js/partytown/") + (o.debug ? "debug/" : ""))[0] && (s = e.querySelectorAll('script[type="text/partytown"]'), i != t ? i.dispatchEvent(new CustomEvent("pt1", {
                detail: t
            })) : (d = setTimeout(f, 1e4), e.addEventListener("pt0", w), r ? h(1) : n.serviceWorker ? n.serviceWorker.register(a + (o.swPath || "partytown-sw.js"), {
                scope: a
            }).then((function(t) {
                t.active ? h() : t.installing && t.installing.addEventListener("statechange", (function(t) {
                    "activated" == t.target.state && h()
                }))
            }), console.error) : f())))
        }

        function h(t) {
            c = e.createElement(t ? "script" : "iframe"), t || (c.setAttribute("style", "display:block;width:0;height:0;border:0;visibility:hidden"), c.setAttribute("aria-hidden", !0)), c.src = a + "partytown-" + (t ? "atomics.js?v=0.7.5" : "sandbox-sw.html?" + Date.now()), e.body.appendChild(c)
        }

        function f(n, r) {
            for (w(), i == t && (o.forward || []).map((function(e) {
                    delete t[e.split(".")[0]]
                })), n = 0; n < s.length; n++)(r = e.createElement("script")).innerHTML = s[n].innerHTML, e.head.appendChild(r);
            c && c.parentNode.removeChild(c)
        }

        function w() {
            clearTimeout(d)
        }
        o = t.partytown || {}, i == t && (o.forward || []).map((function(e) {
            p = t, e.split(".").map((function(e, n, i) {
                p = p[i[n]] = n + 1 < i.length ? "push" == i[n + 1] ? [] : p[i[n]] || {} : function() {
                    (t._ptf = t._ptf || []).push(i, arguments)
                }
            }))
        })), "complete" == e.readyState ? u() : (t.addEventListener("DOMContentLoaded", u), t.addEventListener("load", u))
    }(window, document, navigator, top, window.crossOriginIsolated);
</script>