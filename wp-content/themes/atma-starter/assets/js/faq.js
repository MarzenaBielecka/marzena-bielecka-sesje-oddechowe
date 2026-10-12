const faqButtons = document.querySelectorAll('.atma-faq-question');
let openQuestionId = null;

const setFaqState = (button, open) => {
  const panel = document.getElementById(button.getAttribute('aria-controls'));

  if (!panel) {
    return;
  }

  button.setAttribute('aria-expanded', String(open));
  panel.setAttribute('aria-hidden', String(!open));
  panel.classList.toggle('is-open', open);
};

faqButtons.forEach((button) => {
  button.addEventListener('click', () => {
    openQuestionId = openQuestionId === button.id ? null : button.id;

    faqButtons.forEach((item) => setFaqState(item, item.id === openQuestionId));
  });
});
