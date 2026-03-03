import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, useInnerBlocksProps } from '@wordpress/block-editor';
import { useDispatch, useSelect } from '@wordpress/data';
import { createBlock } from '@wordpress/blocks';
import { Button } from '@wordpress/components';

const ALLOWED_BLOCKS = ['fooz-theme/faq-item'];

const TEMPLATE = [
	['fooz-theme/faq-item', {}],
	['fooz-theme/faq-item', {}],
];

function AddFaqItemButton({ rootClientId }) {
	const { insertBlock } = useDispatch('core/block-editor');
	const innerBlockCount = useSelect(select => select('core/block-editor').getBlockCount(rootClientId), [rootClientId]);

	return (
		<Button
			variant="secondary"
			className="faq-accordion__appender"
			onClick={() => {
				const block = createBlock('fooz-theme/faq-item');
				insertBlock(block, innerBlockCount, rootClientId);
			}}
		>
			{__('+ Add FAQ Item', 'fooz-theme')}
		</Button>
	);
}

export default function Edit({ attributes, setAttributes, clientId }) {
	const { heading } = attributes;

	const blockProps = useBlockProps({
		className: 'faq-accordion',
	});

	const innerBlocksProps = useInnerBlocksProps(
		{ className: 'faq-accordion__items' },
		{
			allowedBlocks: ALLOWED_BLOCKS,
			template: TEMPLATE,
			templateLock: false,
			renderAppender: () => <AddFaqItemButton rootClientId={clientId} />,
		},
	);

	return (
		<div {...blockProps}>
			<RichText
				tagName="h2"
				className="faq-accordion__heading"
				value={heading}
				onChange={value => setAttributes({ heading: value })}
				placeholder={__('Frequently Asked Questions', 'fooz-theme')}
			/>
			<div {...innerBlocksProps} />
		</div>
	);
}
