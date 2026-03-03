const initFaqAccordion = accordion => {
	if (accordion.dataset.faqInitialized) return;
	accordion.dataset.faqInitialized = 'true';

	const items = accordion.querySelectorAll('.wp-block-fooz-theme-faq-item');

	items.forEach((item, index) => {
		const trigger = item.querySelector('.faq-item__trigger');
		const answer = item.querySelector('.faq-item__answer');

		if (!trigger || !answer) return;

		const uid = `faq-${accordion.dataset.faqUid || Date.now()}-${index}`;
		answer.id = uid;
		trigger.setAttribute('aria-controls', uid);

		trigger.addEventListener('click', () => {
			const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
			trigger.setAttribute('aria-expanded', String(!isExpanded));
			answer.hidden = isExpanded;
		});
	});
};

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.wp-block-fooz-theme-faq-accordion').forEach((accordion, i) => {
		accordion.dataset.faqUid = i;
		initFaqAccordion(accordion);
	});
});
