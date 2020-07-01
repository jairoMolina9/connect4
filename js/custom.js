var turn = "blue_player";

function startGame () {

  var tbl = document.getElementById("tblMain");
  if (tbl != null) {
      for (var i = 0; i < tbl.rows.length; i++) {
          for (var j = 0; j < tbl.rows[i].cells.length; j++)
              tbl.rows[i].cells[j].onclick = function () {
                 getval(this);
                 };
      }
  }


}

function getval(cel) {

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

  if(turn == "blue_player") {

    insertBlue(cel);
    document.getElementById("user_turn").innerHTML = "RED PLAYER";
  } else if (turn == "red_player") {
    insertRed(cel);
    document.getElementById("user_turn").innerHTML = "BLUE PLAYER";
  }
}

function insertBlue(cel) {
  cel.className = "tab-col blue-disc";
  turn = "red_player";
}

function insertRed(cel) {
  cel.className = "tab-col red-disc";
  turn = "blue_player";
}

// function startGame() {

// }
