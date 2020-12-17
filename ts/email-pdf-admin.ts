interface WpObject {
	state: any;
	media: any;
}

declare const wp: WpObject;

(() => {
	const addImage = document.getElementById(
		'email-pdf-add-image'
	) as HTMLButtonElement;

	const template = document.getElementById(
		'email-pdf-template'
	) as HTMLTemplateElement;

	const table = document.getElementById(
		'email-pdf-images-table'
	) as HTMLTableElement;

	const rmButtons = table.querySelectorAll<HTMLAnchorElement>('.remove');

	addImage.addEventListener('click', (ev: MouseEvent) => {
		ev.preventDefault();
		const wpMedia = wp
			.media({
				title: 'Select background image for card',
				library: { type: 'image' },
				button: { text: 'Add this image' },
			})
			.on('select', () => {
				wpMedia
					.state()
					.get('selection')
					.each((item: { toJSON: any }) => {
						const json = item.toJSON();
						const clone = template.content.cloneNode(
							true
						) as HTMLTableRowElement;
						const img = clone.querySelector(
							'img'
						) as HTMLImageElement;
						img.src = json.sizes.thumbnail.url;
						img.title = json.title;
						const anchor = clone.querySelector(
							'a'
						) as HTMLAnchorElement;
						anchor.href = json.url;
						const input = clone.querySelector(
							'input'
						) as HTMLInputElement;
						input.value = json.id;

						table.querySelector('tbody').appendChild(clone);
					});
			})
			.open();
	});

	// table.addEventListener('click', (ev: MouseEvent) => {});
})();
