function checkGenres(number) {
	$.ajax({
		url: 'post.php',
		data: {
			request: 'checkgenres',
			number
		},
		method: 'POST',
		dataType : 'json'
	}).done(data => {
		if(!data.message) {
			let filegenre = data.file.join(";");
			let discogsgenre = data.discogs.join(";");
			let correct = filegenre === discogsgenre;
			$('#genre-file-'+number).text(filegenre);
			$('#genre-discogs-'+number).text(discogsgenre);
			$("#save-genre-"+number).prop('disabled', correct);
			$("#library-item-"+number).toggleClass('correct', correct).toggleClass('incorrect', !correct);
		} else {
			$("#library-item-"+number).addClass('warning');
		}
	});
}

function saveGenre(number) {
	let discogsgenre = $('#genre-discogs-'+number).text();
	let genres = discogsgenre.length ? discogsgenre.split(";") : [];
	$.ajax({
		url: 'post.php',
		data: {
			request: 'savegenre',
			number,
			genres
		},
		method: 'POST',
		dataType: 'json'
	}).done(data => {
		if(!data.message) {
			checkGenres(number);
		}
	});
}