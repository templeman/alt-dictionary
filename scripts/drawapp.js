 $(function() {
	 var stage = document.getElementById("stage");

	 // Get the Canvas context
	 var context = stage.getContext('2d');

	 function drawBg() {
		 context.beginPath();
		 context.rect(0, 0, 600, 400);
		 context.closePath();
		 context.fillStyle = "#ffffff";
		 context.fill();
	 }

	 drawBg();

	// function changeColor(element) {
		 $('input#lineColor').keyup(function() {

	  var newBg = $('input#lineColor').val();
	  var regEx = /^([0-9a-f]{1,2}){3}$/i;

	  if (regEx.test(newBg)) {
		  newBg = '#' + newBg;
		  currentColor = newBg;
	  }
		 });
	 // }

	 // Add listener to determine if mouse is clicked on stage
	 $('#stage').mousedown(function(e) {
		 // Get mouse coords    
		 var mouseX = e.pageX - this.offsetLeft;
		 var mouseY = e.pageY - this.offsetTop;

		 paint = true;
		 addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop);
		 draw();
	 });

	 // If mouse is clicked and moving on stage
	 $('#stage').mousemove(function(e) {
		 if(paint) {
	  addClick(e.pageX - this.offsetLeft, e.pageY - this.offsetTop, true);
	  draw();
		 }
	 });

	 // Painting ends on mouseup
	 $('#stage').mouseup(function(e) {
		 paint = false;
	 });

	 // Painting ends on mouseleave
	 $('#stage').mouseleave(function(e) {
		 paint = false;
	 });

	 var clickX = new Array();
	 var clickY = new Array();
	 var clickDrag = new Array();
	 var clickColor = new Array();
	 var currentColor = '#111232';
	 var paint;

	 // The addClick function is what stores all the coords and other info that the mouse covers in a drag
	 function addClick(x, y, dragging) {
		 clickX.push(x);
		 clickY.push(y);
		 clickDrag.push(dragging);
		 clickColor.push(currentColor);
	 }

	 function draw(){
		 stage.width = stage.width; // Clears the canvas before redrawing
		 drawBg();
		 context.lineWidth = 3;
		 context.lineJoin = "round";

		 for(var i=0; i < clickX.length; i++){		
	  context.beginPath();

	  if(clickDrag[i] && i){
		  context.moveTo(clickX[i-1], clickY[i-1]);
	  } else {
		  context.moveTo(clickX[i]-1, clickY[i]);
		 }
		 context.lineTo(clickX[i], clickY[i]);
		 context.closePath();
		 // console.log(context.strokeStyle);
		 context.strokeStyle = clickColor[i]; 
		 context.stroke();
		 }
	 }

	 $('#clearStage').click(function() {

		 // Empty the coord Arrays
		 clickX = new Array();
		 clickY = new Array();
		 clickDrag = new Array();
		 clickColor = new Array();
		 paint = false;
		 context.beginPath();
		 context.clearRect ( 0 , 0 , 600, 400);

		 // Replace bg
		 drawBg();
	 });

	 $('#save').click(function putImage() {
		if(context) {
	 var myImage = context.canvas.toDataURL("image/png");  // Get the data as an image.
		}
		var targetInput = $("input:first").val();
		var myTarget = $.trim(targetInput).toLowerCase();

		// Save image data to file and store on server
	 	$.post(
			'imgsave.php',
			{ img: myImage, target: myTarget },
			function() {
				// build dynamic DOM alert 
				$('#alertBox').text('Image Saved');
				$('#alertBox').slideDown("fast").delay(3000).slideUp("slow");
			});
	 });
 });  

