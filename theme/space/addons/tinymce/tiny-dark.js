(function () {
  var UI_LIGHT_PART     = '/lib/editor/tiny/js/tinymce/skins/ui/oxide/skin.css';
  var UI_DARK_PART      = '/lib/editor/tiny/js/tinymce/skins/ui/oxide-dark/skin.css';
  var IFRAME_DARK_HREF  = M.cfg.wwwroot + '/lib/editor/tiny/js/tinymce/skins/content/dark/content.css';

  var IFRAME_DARK_LINK_ID = 'space-tiny-editor-dark-css';
  var IFRAME_DARK_META_ID = 'space-tiny-editor-dark-meta';

  function pageIsDark() {
    var de = document.documentElement, b = document.body;
    return (
      de.classList.contains('theme-dark') || b.classList.contains('theme-dark') ||
      de.getAttribute('data-theme') === 'dark' || b.getAttribute('data-theme') === 'dark'
    );
  }

  function swapUiSkinLinks(dark) {
    var head = document.head || document.getElementsByTagName('head')[0];
    if (!head) return;

    var links = Array.prototype.slice.call(
      head.querySelectorAll('link[rel="stylesheet"]')
    ).filter(function (lnk) {
      var href = lnk.getAttribute('href') || '';
      return href.indexOf('/lib/editor/tiny/') !== -1 &&
             (href.indexOf('/skins/ui/oxide/skin') !== -1 ||
              href.indexOf('/skins/ui/oxide-dark/skin') !== -1 ||
              /skin(?:=|%3D)(?:oxide|oxide-dark)\b/i.test(href));
    });

    if (!links.length) return;

    links.forEach(function (lnk) {
      var href = lnk.getAttribute('href') || '';
      var newHref = href;

      if (dark) {
        newHref = href
          .replace('/skins/ui/oxide/skin', '/skins/ui/oxide-dark/skin')
          .replace(/skin(=|%3D)oxide\b/i, 'skin$1oxide-dark');
      } else {
        newHref = href
          .replace('/skins/ui/oxide-dark/skin', '/skins/ui/oxide/skin')
          .replace(/skin(=|%3D)oxide-dark\b/i, 'skin$1oxide');
      }

      if (newHref !== href) {
        if (lnk.dataset.spaceSwapped === newHref) return;
        lnk.dataset.spaceSwapped = newHref;
        lnk.href = newHref;
      }
    });
  }

  function ensureIframeMetaDark(doc) {
    var meta = doc.getElementById(IFRAME_DARK_META_ID);
    if (!meta) {
      meta = doc.createElement('meta');
      meta.id = IFRAME_DARK_META_ID;
      meta.name = 'color-scheme';
      meta.content = 'dark';
      doc.head.appendChild(meta);
    } else {
      meta.content = 'dark';
    }
  }

  function setIframeDark(iframe, dark) {
    try {
      var doc = iframe.contentDocument || (iframe.contentWindow && iframe.contentWindow.document);
      if (!doc || !doc.head) return;
      var link = doc.getElementById(IFRAME_DARK_LINK_ID);

      if (dark) {
        if (!link) {
          link = doc.createElement('link');
          link.id = IFRAME_DARK_LINK_ID;
          link.rel = 'stylesheet';
          link.href = IFRAME_DARK_HREF;
          doc.head.appendChild(link);
        }
        ensureIframeMetaDark(doc);
      } else {
        if (link) link.remove();
        var meta = doc.getElementById(IFRAME_DARK_META_ID);
        if (meta) meta.remove();
      }
    } catch (e) {}
  }

  function applyIframeToAll(dark) {
    document.querySelectorAll('.tox-edit-area__iframe').forEach(function (f) {
      setIframeDark(f, dark);
    });
  }

  var queued = false;
  function scheduleApplyAll() {
    if (queued) return;
    queued = true;
    requestAnimationFrame(function () {
      queued = false;
      var dark = pageIsDark();
      swapUiSkinLinks(dark);
      applyIframeToAll(dark);
    });
  }

  function scheduleRetries(ms, times) {
    var n = 0;
    function tick() {
      scheduleApplyAll();
      if (++n < times) setTimeout(tick, ms);
    }
    tick();
  }

  function init() {
    scheduleRetries(200, 20);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  var attrOpts = { attributes: true, attributeFilter: ['class', 'data-theme'] };
  new MutationObserver(function(){ scheduleRetries(120, 10); }).observe(document.documentElement, attrOpts);
  new MutationObserver(function(){ scheduleRetries(120, 10); }).observe(document.body, attrOpts);

  new MutationObserver(function (muts) {
    var addedIframe = muts.some(function(m){
      return Array.prototype.some.call(m.addedNodes || [], function(n){
        return n && (n.classList && n.classList.contains('tox-edit-area__iframe')) ||
               (n.querySelector && n.querySelector('.tox-edit-area__iframe'));
      });
    });
    if (addedIframe) scheduleRetries(120, 5);
  }).observe(document.documentElement, { childList: true, subtree: true });
})();
