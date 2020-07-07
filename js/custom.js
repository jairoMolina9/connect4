try {
  console.log(player_name);
} catch (e) {
  console.log("player_name not defined yet");
}

var current_player = "blue_player";
var finished = false;
var flag = false;
var pregame = false;
var gameID = Date.now();


function startGame () {

  var tbl = document.getElementById("tblMain");
  if (tbl != null) {
      for (var i = 0; i < tbl.rows.length; i++) {
          for (var j = 0; j < tbl.rows[i].cells.length; j++)
              tbl.rows[i].cells[j].onclick = function () {
                if(finished == false)
                 getval(this);
                 };
      }
  }
  if(typeof pregameID !== 'undefined') {
  document.getElementById("game-id").innerHTML = pregameID;
  gameID = pregameID;
} else {
  document.getElementById("game-id").innerHTML = gameID;
}
}

function getval(cel) {
  if(!flag) {
    newGame();
    flag = true;
  }

  var data = cel.id;
  var col_numb = data.charAt(data.length-1);

  var tbl = document.getElementById("tblMain");

  for(var i = tbl.rows.length-1 ; i >= 0 ;i--) {
    var class_name = tbl.rows[i].cells[col_numb].className;

    if(class_name != "tab-col blue-disc" && class_name != "tab-col red-disc") {
      cel = tbl.rows[i].cells[col_numb];
      play(cel);
      break;
    }
  }
}

function play(cel) {

  if(current_player == "blue_player") {
    insertBlue(cel);

    if(checkWinner()) {
      if(typeof player_name !== 'undefined') {
          document.getElementById("user_winner").innerHTML = player_name + " WON";
      } else {

      document.getElementById("user_winner").innerHTML = "BLUE PLAYER WON";
    }
      finished = true;
      sendWinner(current_player);
      document.getElementById("restart").innerHTML = "NEW GAME"; //new button
      document.getElementById("restart").setAttribute("onclick","resetGame('newgame')");

    } else {
    document.getElementById("user_turn").innerHTML = "RED PLAYER";
    }

  } else if (current_player == "red_player") {
    insertRed(cel);

    if(checkWinner()) {
      document.getElementById("user_winner").innerHTML = "RED PLAYER WON";
      finished = true;
      sendWinner(current_player);
      document.getElementById("restart").innerHTML = "NEW GAME"; //new button
      document.getElementById("restart").setAttribute("onclick","resetGame('newgame')");

    } else {
      if(typeof player_name !== 'undefined') {
          document.getElementById("user_turn").innerHTML = player_name;
      } else {

        document.getElementById("user_turn").innerHTML = "BLUE PLAYER";
      }
    }
  }
}

function newGame() {
  var name = "";
  if(typeof player_name !== 'undefined') {
    name = player_name;
  } else {
    name = "guest";
  }
  $.ajax({
    type: 'POST',
    url: '../game.php',
    data: {
      newgame: gameID,
      playername: name
    },
    success: function(data) {
      //handle.innerHTML = data;
      console.log("newGame()");
    },
    error: function (jqXHR) {
      handle.innerText = 'Error: ' + jqXHR.status;
    }
  });
}

function sendWinner(current_player){
  var tmp_name = "";

  //checks if player has name and checks winner (whoever had prev turn)
  if(typeof player_name !== 'undefined') {
    if(current_player == "red_player"){
      tmp_name = player_name;
    } else {
      tmp_name = "red_player";
    }
  } else {
      if(current_player == "red_player"){
          tmp_name = "blue_player";
        } else {
          tmp_name = "red_player";
        }
  }

  $.ajax({
    type: 'POST',
    url: '../game.php',
    data: {
      winner: tmp_name,
      w_game_id: gameID
    },
    success: function(data) {
      //handle.innerHTML = data;
      console.log("sendWinner()");
    },
    error: function (jqXHR) {
      handle.innerText = 'Error: ' + jqXHR.status;
    }
  });
}

function setPregame(){

  if(typeof gamedata !== 'undefined') {
    pregame = true;
    gameID = pregameID;

    for(var x = 0; x < gamedata.length; x++) {

      if(gamedata[x][1] == 'blue_player') {
        document.getElementById(gamedata[x][0]).className = 'tab-col blue-disc';
      } else {
        document.getElementById(gamedata[x][0]).className = 'tab-col red-disc';
      }

    }

    if(gamedata[gamedata.length-1][1] == 'blue_player') {
      current_player = "red_player";
    }

    } else {
      console.log("no gamedata");
    }

    if(typeof pregamewinner !== 'undefined') {
      if(pregamewinner[0] !== null) {
        document.getElementById("restart").innerHTML = "NEW GAME"; //new button
        document.getElementById("restart").setAttribute("onclick","resetGame('newgame')");

        finished = true;

         if (pregamewinner[0] == 'blue_player') {
         displayBlue();
          document.getElementById("user_winner").innerHTML = "BLUE PLAYER WON";
        } else if (pregamewinner[0] == 'red_player') {
        displayRed();
          document.getElementById("user_winner").innerHTML = "RED PLAYER WON";
        } else {
        displayBlue();
          document.getElementById("user_winner").innerHTML = pregamewinner[0] + " WON";
        }
      }
    }
}

function resetGame(task) {
  if(task == 'restart') {
    $.ajax({
      type: 'POST',
      url: '../game.php',
      data: {
        restart: gameID
      },
      success: function(data) {
        //handle.innerHTML = data;
        console.log("resetGame()");
      },
      error: function (jqXHR) {
        handle.innerText = 'Error: ' + jqXHR.status;
      }
    });
  }
  document.getElementById("restart").setAttribute("onclick","resetGame('restart')");
  document.getElementById("restart").innerHTML = "RESTART";


  flag = false;
  gameID = Date.now();
  var tbl = document.getElementById("tblMain");
  document.getElementById("game-id").innerHTML = gameID;

  if(typeof player_name !== 'undefined') {
      document.getElementById("user_turn").innerHTML = player_name;
  } else {
    document.getElementById("user_turn").innerHTML = "BLUE PLAYER";
  }
  document.getElementById("user_turn").className = "blue-title";

  document.getElementById("user_winner").innerHTML = "";

  current_player = "blue_player";
  finished = false;

  for(var row = 0 ; row < tbl.rows.length; row++) {
    for(var col = 0; col <tbl.rows[row].cells.length; col++) {
      tbl.rows[row].cells[col].className = "tab-col";
    }
  }
}

function sendMovement(cel) {
  var handle = document.getElementById('response');
  var info = [gameID, cel.id, current_player];

  $.ajax({
    type: 'POST',
    url: '../game.php',
    data: {
      gameID: info[0],
      coord: info[1],
      player: info[2]
    },
    success: function(data) {
      //handle.innerHTML = data;
      console.log("sendMovement()");
    },
    error: function (jqXHR) {
      handle.innerText = 'Error: ' + jqXHR.status;
    }
  });
}


function insertBlue(cel) {
  cel.className = "tab-col blue-disc";
  sendMovement(cel);
  current_player = "red_player";
}

function insertRed(cel) {
  cel.className = "tab-col red-disc";
  sendMovement(cel);
  current_player = "blue_player";
}

function checkWinner(){
  var winner_exists = false;

  var tbl = document.getElementById("tblMain");

  if(checkHorizontal(tbl) || checkVertical(tbl) || checkDiagonal_1(tbl) || checkDiagonal_2(tbl))
    winner_exists = true;

    return winner_exists;

}

function checkHorizontal(tbl) {
  var total_numb = 0;

  var classname;

  if(current_player == "blue_player") {
    classname = "tab-col red-disc";
  } else {
    classname = "tab-col blue-disc";
  }

  for(var row = 0; row < tbl.rows.length ; row++) {

    for(var col = 0; col < tbl.rows[row].cells.length; col++) {
      if(tbl.rows[row].cells[col].className == classname) {
        total_numb++;

        if(total_numb >= 4)
          return true;

      } else {
        total_numb = 0;
      }

    }

    total_numb = 0;

}
  return false;
}

function checkVertical(tbl) {
  var total_numb = 0;

  var classname;

  if(current_player == "blue_player") {
    classname = "tab-col red-disc";
  } else {
    classname = "tab-col blue-disc";
  }

    for(var col = 0 ; col < tbl.rows[0].cells.length; col++) {

      for(var row = 0; row < tbl.rows.length ; row++) {

        if(tbl.rows[row].cells[col].className == classname) {
          total_numb++;

          if(total_numb >= 4)
            return true;
        } else {
          total_numb = 0;
        }

      }
      total_numb = 0;
    }
    return false;
  }

  //left right to right bottom
  function checkDiagonal_1(tbl) {
    var total_numb = 0;

    var classname;

    if(current_player == "blue_player") {
      classname = "tab-col red-disc";
    } else {
      classname = "tab-col blue-disc";
    }

    for( var row = 0 ; row <= tbl.rows[0].cells.length + tbl.rows.length - 2; row++ ) {

      for( var col = 0 ; col <= row ; col++ ) {

        var tmp = row - col;

        if( tmp < tbl.rows.length && col < tbl.rows[0].cells.length ) {
          if(tbl.rows[tmp].cells[col].className == classname) {
            total_numb++;

            if(total_numb >= 4)
              return true;
          } else {
            total_numb = 0;
          }

        }
      }

      total_numb = 0;
    }
    return false;
  }

  //bottom left to top right
  function checkDiagonal_2(tbl) {
    var total_numb = 0;

    var classname;

    if(current_player == "blue_player") {
      classname = "tab-col red-disc";
    } else {
      classname = "tab-col blue-disc";
    }

    for (var col = 0; col <= 2 * (tbl.rows[0].cells.length - 1); ++col) {
    for (var row = tbl.rows.length - 1; row >= 0; --row) {
        var tmp = col - (tbl.rows.length - row);
        if (tmp >= 0 && tmp < tbl.rows[0].cells.length) {
          if(tbl.rows[row].cells[tmp].className == classname) {
            total_numb++;

            if(total_numb >= 4)
              return true;
          } else {
            total_numb = 0;
          }
        }
    }
  total_numb = 0;
  }
  return false;
}

function displayBlue() {
  document.getElementById("user_winner").className = "blue-title";
  document.getElementById("user_turn").className = "blue-title";
  document.getElementById("game-id-header").className = "blue-title";
  document.getElementById("game-id").className = "blue-title";
  document.getElementById("restart").className = "neon-button-blue";
  document.getElementById("game-container").className = "container game-blue-ct";
  document.getElementById("game-heading").className = "blue-heading";

  if(typeof player_name !== 'undefined') {
    document.getElementById("logout-btn").className = "neon-button-blue";
  } else {
    document.getElementById("exit-btn").className = "neon-button-blue";
  }
}

function displayRed(){
  document.getElementById("user_winner").className = "red-title";
  document.getElementById("user_turn").className = "red-title";
  document.getElementById("game-id-header").className = "red-title";
  document.getElementById("game-id").className = "red-title";
  document.getElementById("restart").className = "neon-button";
  document.getElementById("game-container").className = "container game-red-ct";
  document.getElementById("game-heading").className = "red-heading";

  if(typeof player_name !== 'undefined') {
    document.getElementById("logout-btn").className = "neon-button";
  } else {
    document.getElementById("exit-btn").className = "neon-button";
  }
}

function getLoginForm() {
  document.getElementById("login-form").style.display="block";
  document.getElementById("register-form").style.display="none";
  document.getElementById("guest-form").style.display="none";
}

function getRegisterForm() {
  document.getElementById("login-form").style.display="none";
  document.getElementById("register-form").style.display="block";
  document.getElementById("guest-form").style.display="none";
}

function getGuestForm() {
  document.getElementById("login-form").style.display="none";
  document.getElementById("register-form").style.display="none";
  document.getElementById("guest-form").style.display="block";
}
