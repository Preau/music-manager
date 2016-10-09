function findGenre(number) {
	$.ajax({
		url: 'post.php',
		data: {
			request: 'findgenre',
			number
		},
		method: 'POST',
		dataType : 'json'
	}).done(data => {
		if(!data.message) {
			let genre = data.genres.concat(data.styles.filter(item => {
				return data.genres.indexOf(item) < 0;
			}));
			$("#genre-"+number).text(genre);
		}
	});
}

function saveGenre(number) {
	$.ajax({
		url: 'post.php',
		data: {
			request: 'savegenre',
			number
		},
		method: 'POST',
		dataType: 'json'
	}).done(data => {
		if(!data.message) {
			console.log(data);
		}
	});
}