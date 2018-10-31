export const formatDate = ( timestamp, style = 'long' ) => {
	const now = new Date();
	const datetime = new Date( timestamp );
	const postDay = datetime.getUTCDate();
	const postYear = datetime.getUTCFullYear();

	// if within 24 hours
	const elapsed = now - datetime;
	if ( 60 * 60 * 24 * 1000 >= Math.abs( elapsed ) ) {
		let number;
		const msPerMinute = 60 * 1000;
		const msPerHour = msPerMinute * 60;
		const msPerDay = msPerHour * 24;

		if ( elapsed < msPerHour ) {
			number = Math.round( elapsed / msPerMinute );
			if ( 0 === number ) {
				return 'just now';
			}

			return 1 === number ? 'a minute ago' : `${number} minutes ago`;
		} else if ( elapsed < msPerDay ) {
			number = Math.round( elapsed / msPerHour );
			return 1 === number ? 'an hour ago' : `${number} hours ago`;
		}
	}

	const months = [
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December',
	];

	return 'short' === style && datetime.getFullYear() === postYear
		? `${months[datetime.getMonth()]} ${postDay}`
		: `${months[datetime.getMonth()]} ${postDay}, ${postYear}`;
};

export default {
	formatDate,
};
