$(function() {
	init_split();
	getFiles();
});

function getFiles() {
	$.ajax({
		url: 'index.php',
		type: 'POST',
		dataType: 'json',
		data: {
			action: "getFiles",
			emp_id: 56532
		},
		beforeSend: function(){
			$("#fileSidebar .loading").addClass('d-flex');
		}
	})
	.done(function(RES) {
		console.log(RES);
		// filesHtml("#fileSidebar", RES);
		$("#fileSidebar .fileExplorer").html("Result is loaded in console");
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.error(jqXHR, textStatus, errorThrown);
		$("#fileSidebar .fileExplorer").html("Error Loading Data");
	})
	.always(function() {
		$("#fileSidebar .loading").removeClass('d-flex');
	});
}

/* In Progress ---
function filesHtml(ele, RES) {
	var fileTreeObj = {};
	var breakObject = function(obj, result){
		if(isObject(obj)){
			$.each(obj, function(key, val) {
				if(isObject(val)){
					result[key] = breakObject(val);
				}
				else{
					result[key] = val;
				}
			});
		}
	};

	var breakArray = function(arr, result){
		if(isArray(arr)){
			$.each(arr, function(ind, val) {
				var sp = val.split("/");
				$.each(sp, function(index, value) {
					if(result[value]){
						result[value].push()
					}
				});
			});
		}
	};
}
*/

function init_split() {
	var sizes = localStorage.getItem('split-sidebar')

	if (sizes) {
		sizes = JSON.parse(sizes)
	} else {
		sizes = [33, 67] // default sizes
	}

	var split = Split(['#fileSidebar', '#fileDetail'], {
		sizes: sizes,
		onDragEnd: function(sizes) {
			localStorage.setItem('split-sidebar', JSON.stringify(sizes))
		},
		elementStyle: function(dimension, size, gutterSize) {
			return {
				'flex-basis': 'calc(' + size + '% - ' + gutterSize + 'px)',
			}
		},
		gutterStyle: function(dimension, gutterSize) {
			return {
				'flex-basis': gutterSize + 'px',
			}
		},
		minSize: [250,400],
		expandToMin: true,
	});
}