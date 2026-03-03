import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit({ attributes, setAttributes }) {
	const { question, answer } = attributes;

	const blockProps = useBlockProps({
		className: 'faq-item',
	});

	return (
		<div {...blockProps}>
			<div className="faq-item__header">
				<span className="faq-item__number" aria-hidden="true"></span>
				<RichText
					tagName="span"
					className="faq-item__question-text"
					value={question}
					onChange={value => setAttributes({ question: value })}
					placeholder={__('Enter your question here…', 'fooz-theme')}
					allowedFormats={[]}
				/>
			</div>
			<div className="faq-item__answer-wrap">
				<RichText
					tagName="p"
					className="faq-item__answer-content"
					value={answer}
					onChange={value => setAttributes({ answer: value })}
					placeholder={__('Enter your answer here…', 'fooz-theme')}
				/>
			</div>
		</div>
	);
}
