(function () {
  'use strict';

  function canTrack() {
    return typeof window.gtag === 'function';
  }

  function safeText(value, fallback) {
    var text = String(value || '').trim();
    return text ? text.slice(0, 100) : fallback;
  }

  function formName(form) {
    return safeText(form && (form.id || form.getAttribute('name')), 'unnamed_form');
  }

  function isLeadForm(form) {
    if (!form || form.tagName !== 'FORM') return false;
    var action = String(form.getAttribute('action') || '').toLowerCase();
    var leadIds = ['contact-form', 'services-intake-form', 'sidebar-intake-form', 'group-booking-form', 'individual-booking-form'];
    return action.indexOf('/api/contact') !== -1 || leadIds.indexOf(form.id) !== -1 || form.hasAttribute('data-lead-form');
  }

  function send(eventName, parameters) {
    if (!canTrack()) return;
    var payload = Object.assign({ page_path: window.location.pathname }, parameters || {});
    window.gtag('event', eventName, payload);
  }

  window.BrightAnalytics = {
    event: send,
    leadSuccess: function (form, leadType) {
      send('generate_lead', {
        currency: 'VND',
        value: 0,
        form_id: formName(form),
        lead_type: safeText(leadType, 'contact')
      });
    }
  };

  document.addEventListener('click', function (event) {
    var link = event.target.closest('a[href]');
    if (!link) return;
    var href = link.getAttribute('href') || '';
    if (href.toLowerCase().indexOf('tel:') === 0) {
      send('click_to_call', { link_location: safeText(link.getAttribute('aria-label') || link.textContent, 'phone_link') });
    } else if (href.toLowerCase().indexOf('mailto:') === 0) {
      send('click_email', { link_location: safeText(link.getAttribute('aria-label') || link.textContent, 'email_link') });
    }
  });

  document.addEventListener('copy', function () {
    var selected = window.getSelection ? String(window.getSelection()) : '';
    if (/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b/i.test(selected)) {
      send('copy_email', { content_type: 'email' });
    }
  });

  var startedForms = new WeakSet();
  document.addEventListener('focusin', function (event) {
    var form = event.target && event.target.closest ? event.target.closest('form') : null;
    if (!isLeadForm(form) || startedForms.has(form)) return;
    if (!event.target.matches('input, select, textarea')) return;
    startedForms.add(form);
    send('lead_form_start', { form_id: formName(form) });
  });

  document.addEventListener('submit', function (event) {
    if (!isLeadForm(event.target)) return;
    send('form_submit_attempt', { form_id: formName(event.target) });
  }, true);

  var reached = {};
  var thresholds = [25, 50, 75, 90];
  var ticking = false;

  function checkScrollDepth() {
    ticking = false;
    var documentHeight = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
    var scrollable = documentHeight - window.innerHeight;
    if (scrollable <= 0) return;
    var percent = Math.round((window.scrollY / scrollable) * 100);
    thresholds.forEach(function (threshold) {
      if (percent >= threshold && !reached[threshold]) {
        reached[threshold] = true;
        send('scroll_depth', { percent_scrolled: threshold });
      }
    });
  }

  window.addEventListener('scroll', function () {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(checkScrollDepth);
  }, { passive: true });
})();
