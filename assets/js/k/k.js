var ak = {
  37: 'left',
  38: 'up',
  39: 'right',
  40: 'down',
  65: 'a',
  66: 'b'
};
var kc = ['up', 'up', 'down', 'down', 'left', 'right', 'left', 'right', 'b', 'a'];
var kp = 0;
document.addEventListener('keydown', function(e) {
  var key = ak[e.keyCode];
  var rk = kc[kp];
  if (key == rk) {
    kp++;
    if (kp == kc.length) {
      //acc();
      doKonShow();
      kp = 0;
    }
  } else {
    kp = 0;
  }
});

function acc() {
	var element = document.body;
	element.classList.add("kflip");
	var konDiv = document.createElement('div');
	konDiv.classList.add("kon");
	konDiv.classList.add("konblink");
	konDiv.innerHTML = "<img src=\"/assets/js/k/go.png\" width=\"300px\">";
	document.body.appendChild(konDiv);
}


function doKonShow() {
	console.log("do show here...");
	
	var element = document.body;
	var konDiv = document.createElement('div');
	konDiv.classList.add("konGrayOut");
	
	var inKonDiv = document.createElement('div');
	inKonDiv.classList.add("kon");
	inKonDiv.innerHTML = "<img src=\"/assets/js/k/m.png\" width=\"300px\">";
	konDiv.appendChild(inKonDiv);
	
	document.body.appendChild(konDiv);
	
}
