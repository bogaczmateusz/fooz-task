import { useBlockProps, RichText } from '@wordpress/block-editor';

// Asset 1 — Chevron Down (collapsed state)
const ChevronDown = () => (
	<svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		fill="none"
		stroke="currentColor"
		strokeWidth="2"
		strokeLinecap="round"
		strokeLinejoin="round"
		className="faq-item__icon faq-item__icon--chevron-down"
		aria-hidden="true"
		focusable="false"
	>
		<polyline points="6 9 12 15 18 9" />
	</svg>
);

// Asset 2 — Chevron Up (expanded state)
const ChevronUp = () => (
	<svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		fill="none"
		stroke="currentColor"
		strokeWidth="2"
		strokeLinecap="round"
		strokeLinejoin="round"
		className="faq-item__icon faq-item__icon--chevron-up"
		aria-hidden="true"
		focusable="false"
	>
		<polyline points="18 15 12 9 6 15" />
	</svg>
);

export default function Save({ attributes }) {
	const { question, answer } = attributes;

	const blockProps = useBlockProps.save({
		className: 'faq-item',
	});

	return (
		<div {...blockProps}>
			<button className="faq-item__trigger" aria-expanded="false">
				<span className="faq-item__number" aria-hidden="true"></span>
				<RichText.Content tagName="span" className="faq-item__question-text" value={question} />
				<span className="faq-item__icons" aria-hidden="true">
					<ChevronDown />
					<ChevronUp />
				</span>
			</button>
			<div className="faq-item__answer" hidden>
				<RichText.Content tagName="p" className="faq-item__answer-content" value={answer} />
			</div>
		</div>
	);
}
