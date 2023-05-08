/* eslint-disable */
function stringEscape(s) {
	return s
		? s
				.replace(/\\/g, '\\\\')
				.replace(/\n/g, '\\n')
				.replace(/\t/g, '\\t')
				.replace(/\v/g, '\\v')
				.replace(/'/g, "\\'")
				.replace(/"/g, '\\"')
				.replace(/[\x00-\x1F\x80-\x9F]/g, hex)
		: s;
	function hex(c) { const v = '0'+c.charCodeAt(0).toString(16); return '\\x'+v.substring(v.length-2); }
}
/* eslint-enable */

export default function manageInlineScripts(scriptArray) {
	if (scriptArray && scriptArray.length > 0) {
		scriptArray.forEach(inlineScript => {
			try {
				// eslint-disable-next-line no-eval
				eval(stringEscape(inlineScript));
			} catch {
				console.error(
					`ERROR RUNNING INLINE SCRIPT. LIKELY PROBLEM WITH ESCAPE CHARS -> '${inlineScript}'`,
				);
			}
		});
	}
}
