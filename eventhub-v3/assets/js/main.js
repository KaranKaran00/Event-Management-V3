document.addEventListener('DOMContentLoaded', function () {
  var navToggle = document.getElementById('navToggle');
  var mainNav = document.getElementById('mainNav');
  if (navToggle && mainNav) {
    navToggle.addEventListener('click', function () {
      var isOpen = mainNav.classList.toggle('is-open');
      navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  var stepper = document.querySelector('.qty-stepper');
  if (stepper) {
    var unitPrice = parseFloat(stepper.dataset.price || '0');
    var qtyValueEl = stepper.querySelector('.qty-value');
    var totalEl = document.querySelector('.ticket-total-value');
    var qty = 1;

    var formatPrice = function (amount) {
      if (amount <= 0) return 'Free';
      return '₹' + amount.toLocaleString('en-IN');
    };

    var updateTotal = function () {
      qtyValueEl.textContent = qty;
      if (totalEl) totalEl.textContent = formatPrice(unitPrice * qty);
    };

    stepper.querySelectorAll('.qty-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        if (btn.dataset.action === 'increase' && qty < 10) qty++;
        if (btn.dataset.action === 'decrease' && qty > 1) qty--;
        updateTotal();
      });
    });
  }

  var getTicketsBtn = document.getElementById('getTicketsBtn');
  var ticketModal = document.getElementById('ticketModal');
  var closeModalBtn = document.getElementById('closeModalBtn');

  if (getTicketsBtn && ticketModal) {
    getTicketsBtn.addEventListener('click', function () {
      ticketModal.classList.add('is-open');
    });
  }
  if (closeModalBtn && ticketModal) {
    closeModalBtn.addEventListener('click', function () {
      ticketModal.classList.remove('is-open');
    });
    ticketModal.addEventListener('click', function (e) {
      if (e.target === ticketModal) ticketModal.classList.remove('is-open');
    });
  }
});
