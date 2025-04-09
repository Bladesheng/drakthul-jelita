const czechCharMap: Record<string, string> = {
	á: 'a',
	č: 'c',
	ď: 'd',
	é: 'e',
	ě: 'e',
	í: 'i',
	ň: 'n',
	ó: 'o',
	ř: 'r',
	š: 's',
	ť: 't',
	ú: 'u',
	ů: 'u',
	ý: 'y',
	ž: 'z',
};

const searchInput = document.querySelector<HTMLInputElement>('#search');
const screenshots = document.querySelectorAll<HTMLElement>('.screenshot');

searchInput?.addEventListener('input', () => {
	const inputValue = toAsciiLowercase(searchInput.value);

	screenshots.forEach((screenshot) => {
		const wowName = toAsciiLowercase(screenshot.getAttribute('data-wow-name')!);

		if (wowName.includes(inputValue) || inputValue === '') {
			screenshot.style.display = '';
		} else {
			screenshot.style.display = 'none';
		}
	});
});

function toAsciiLowercase(str: string): string {
	return str.toLowerCase().replace(/[áčďéěíňóřšťúůýž]/g, (char) => czechCharMap[char] ?? char);
}
