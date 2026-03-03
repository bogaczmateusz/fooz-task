import { registerBlockType } from '@wordpress/blocks';

import metadata from './block.json';
import Edit from './edit.js';
import Save from './save.js';
import './style.scss';

registerBlockType(metadata.name, {
	edit: Edit,
	save: Save,
});
