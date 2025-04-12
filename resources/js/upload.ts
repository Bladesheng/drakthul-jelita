import { createWorker, PSM } from 'tesseract.js';

let abortController = new AbortController();
let timeout: number;

document.querySelector('.uploadForm')!.addEventListener('input', checkNameClassCombination);

document.querySelector('#screenshot')?.addEventListener('change', (e) => {
	const target = e.target as HTMLInputElement;

	if (target?.files?.[0]) {
		handleFile(target.files[0]);
	}
});

document.addEventListener('paste', (e) => {
	const item = e.clipboardData?.items[0];

	if (item?.type.startsWith('image/')) {
		const file = item.getAsFile();
		if (file) {
			const dataTransfer = new DataTransfer();
			dataTransfer.items.add(file);
			document.querySelector<HTMLInputElement>('#screenshot')!.files = dataTransfer.files;

			handleFile(file);
		}
	}
});

function checkNameClassCombination() {
	const formData = new FormData(document.querySelector<HTMLFormElement>('.uploadForm')!);
	const wowName = formData.get('wowName') as string | null;
	const wowClassId = formData.get('wowClassId') as string | null;

	clearTimeout(timeout);

	abortController.abort();
	abortController = new AbortController();
	const signal = abortController.signal;

	if (!wowName || !wowClassId || wowName.length < 2) {
		return;
	}

	timeout = setTimeout(async () => {
		try {
			const res = await fetch(`/screenshots/search?wowName=${wowName}&wowClassId=${wowClassId}`, {
				signal,
			});
			const data = await res.json();

			const nameInput = document.querySelector<HTMLInputElement>('#wowName')!;
			const nameLabel = document.querySelector<HTMLLabelElement>('label.input')!;
			if (data.length) {
				nameLabel.classList.remove('input-success');
				nameLabel.classList.add('input-error');
				nameInput.setCustomValidity('Screenshot with that name and class already exists');
				nameInput.reportValidity();
			} else {
				nameLabel.classList.add('input-success');
				nameLabel.classList.remove('input-error');
				nameInput.setCustomValidity('');
			}
		} catch (err) {
			if ((err as DOMException).name === 'AbortError') {
				return;
			}

			console.error(err);
		}
	}, 200);
}

async function handleFile(file: File) {
	const reader = new FileReader();
	reader.onload = (e) => {
		if (typeof e.target?.result === 'string') {
			document.querySelector<HTMLImageElement>('img.screenshot')!.src = e.target.result;
		}
	};
	reader.readAsDataURL(file);

	const worker = await createWorker('eng');

	await worker.setParameters({
		// https://github.com/tesseract-ocr/tesseract/blob/4.0.0/src/ccstruct/publictypes.h#L163
		tessedit_pageseg_mode: PSM.SINGLE_WORD,

		tessedit_char_whitelist: 'abcdefghijklmnopqrstuvwxyz' + 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
		// 'áÁàÀâÂäÄåÅªæÆçÇœŒéÉèÈêÊëËƒíÍìÌîÎïÏñÑóÓòÒôÔöÖºúÚùÙûÛÜýÝÿ',
	});

	const result = await worker.recognize(file);
	const name = result.data.text;
	const nameFormatted = name.charAt(0).toUpperCase() + name.slice(1).toLowerCase();

	const nameInput = document.querySelector<HTMLInputElement>('#wowName')!;

	nameInput.value = nameFormatted;
	nameInput.focus();

	await worker.terminate();
}
