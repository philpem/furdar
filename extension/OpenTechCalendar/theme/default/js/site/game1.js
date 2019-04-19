var gameData;

$( document ).ready(function() {
	loadGame();
});

function loadGame() {
	$('#intro').hide();
	$('#instructions').show();
	$.ajax({
		dataType: "json",
		url: "/game/data.json",
		success: function(data) {
			$('#instructions #loading').hide();
			$('#instructions #startFirstGame').show();
			gameData = data;
		}
	});
}

var gameInProgress = false;
var currentWeek, currentDay, squaresSeen, movesMade, currentScore;
var movesAllowed = 10;
var maxScore = 0;

function startFirstGame() {
	$('#instructions').hide();
	$('#gameState').show();
	$('#gameBoardWrapper').show();
	$('#eventsListWrapper').show();
	$('#headerWrapper').addClass('inGame');
	startGame();
	$(document).on('keydown.close_popup', function(e) {
		if (gameInProgress) {
			if(e.keyCode == 65) {
				moveLeft();
			} else if(e.keyCode == 68) {
				moveRight();
			} else if(e.keyCode == 87) {
				moveUp();
			} else if(e.keyCode == 83) {
				moveDown();
			}
		}
		if (e.keyCode == 27) {
			$('#gameBoardFlashWrapper').hide();
		}
	});
}

function startGame() {
	gameInProgress = true;
	currentWeek = parseInt(Math.random() * 7)+1;
	currentDay =  parseInt(Math.random() * 7)+1;
	movesMade = 0;
	currentScore = 0;
	$('#eventsList').empty();
	$('#playAgain').hide();
	$('#browseEvents').hide();
	clearSquaresSeenStore();
	// do not markSquareSeen(currentWeek, currentDay); 
	// as this means player can't go back and uncover it.
	draw();
}


function cellClicked(week,day) {
	if (gameInProgress && isMoveLegal(week,day)) {
		moveTo(week, day);
	}
}

function moveLeft() {
	if (currentDay > 1) {
		moveTo(currentWeek, currentDay - 1);
	}
}

function moveRight() {
	if (currentDay < 7) {
		moveTo(currentWeek, currentDay + 1);
	}
}

function moveUp() {
	if (currentWeek > 1) {
		moveTo(currentWeek -1, currentDay);
	}
}

function moveDown() {
	if (currentWeek < 7) {
		moveTo(currentWeek +1, currentDay);
	}
}


function moveTo(week,day) {
	currentDay = day;
	currentWeek = week;
	movesMade += 1;
	if (!isSquareSeen(week, day)) {
		// uncover square
		var thisScore = gameData['squares'][week][day]['events'].length;
		if (thisScore > 0) {
			currentScore += thisScore;
			var html = '';
			var htmlFlash = '';
			for(i in gameData['squares'][week][day]['events']) {
				var eventData = gameData['squares'][week][day]['events'][i];
				html += '<li class="event">';
				html += '<a href="/event/'+eventData.slug+'/" target="_blank">';
				html += '<span class="date">'+gameData['squares'][week][day]['date']+'</span>';
				html += '<span class="summary">'+escapeHTML(eventData.summary)+'</span>';
				html += '</a>';
				html += '</li>';
				htmlFlash += '<li class="event">';
				htmlFlash += '<a href="/event/'+eventData.slug+'/" target="_blank">';
				htmlFlash += '<span class="summary">'+escapeHTML(eventData.summary)+'</span>';
				htmlFlash += '</a>';
				htmlFlash += '</li>';
			}
			$('#eventsList').prepend(html);
			showFlash('<p>You found:</p><ul class="eventsList">'+htmlFlash+'</ul>',thisScore);
		}
	}
	markSquareSeen(week, day);
	if (movesMade >= movesAllowed) {
		gameOver();
	}
	draw();
}

function gameOver() {
	gameInProgress = false;
	if (currentScore > maxScore) {
		maxScore = currentScore;
	}
	$('#playAgain').show();
	$('#browseEvents').show();
}

function draw() {
	for (var week = 1; week < 8; week++) {
		for (var day = 1; day < 8; day++) {
			var cell = $('#Week'+week+'Day'+day);
			if (week == currentWeek && day == currentDay) {
				cell.html('<img src="/theme/default/img/gamePlayer1.png">');
			} else if (isMoveLegal(week, day) && gameInProgress) {
				cell.html('<img src="'+getMovePosibilitiesGraphic(week,day)+'">');
			} else {
				cell.html('&nbsp;');
			}
			if (isSquareSeen(week, day)) {
				cell.addClass("squareSeen");
				cell.removeClass("squareNotSeen");
			} else {
				cell.addClass("squareNotSeen");
				cell.removeClass("squareSeen");
			}
		}
	}
	$('#gameStateMovesLeft').html(movesAllowed - movesMade);
	$('#gameStateCurrentScore').html(currentScore);
	$('#gameStateMaxScore').html(maxScore);
}

function isMoveLegal(week,day) {
	if (week == currentWeek && day == currentDay - 1) {
		return true;
	}
	if (week == currentWeek && day == currentDay + 1) {
		return true;
	}
	if (week == currentWeek - 1 && day == currentDay) {
		return true;
	}
	if (week == currentWeek + 1 && day == currentDay) {
		return true;
	}
	return false;
}

function getMovePosibilitiesGraphic(week,day) {
	if (week == currentWeek && day == currentDay - 1) {
		return '/theme/default/img/gameLeft.png';
	}
	if (week == currentWeek && day == currentDay + 1) {
		return '/theme/default/img/gameRight.png';
	}
	if (week == currentWeek - 1 && day == currentDay) {
		return '/theme/default/img/gameUp.png';
	}
	if (week == currentWeek + 1 && day == currentDay) {
		return '/theme/default/img/gameDown.png';
	}
}

function clearSquaresSeenStore() {
	squaresSeen = new Array(80);
	for (var i = 1; i < 80; i++) {
		squaresSeen[i] = false;
	}
}

function markSquareSeen(week,day) {
	squaresSeen[week*10 + day] = true;
}

function isSquareSeen(week, day) {
	return squaresSeen[week*10 + day];
}


function escapeHTML(str) {
	var div = document.createElement('div');
	div.appendChild(document.createTextNode(str));
	return div.innerHTML;
}

var flashTimer;
function showFlash(html, sizeOfMessage) {
	if (flashTimer) {
		window.clearTimeout(flashTimer);
	}
	$('#gameBoardFlashContent').html(html);
	$('#gameBoardFlashWrapper').show();
	var timeOut = Math.min(4000, sizeOfMessage*1500);
	flashTimer=setTimeout(function(){ $('#gameBoardFlashWrapper').hide();},timeOut);
}
