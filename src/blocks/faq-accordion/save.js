import { useBlockProps, RichText, InnerBlocks } from '@wordpress/block-editor';

export default function Save({ attributes }) {
	const { heading } = attributes;

	const blockProps = useBlockProps.save({
		className: 'faq-accordion',
	});

	return (
		<div {...blockProps}>
			<RichText.Content tagName="h2" className="faq-accordion__heading" value={heading} />
			<div className="faq-accordion__items">
				<InnerBlocks.Content />
			</div>
		</div>
	);
}
