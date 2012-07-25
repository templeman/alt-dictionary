$(function() {

	var clicked, canvas, ctx, coords, offsetX, offsetY, oldX, oldY;

	setupCanvas();
	canvas.onmousemove = handleMouseMove;

	window.onmousedown = 
