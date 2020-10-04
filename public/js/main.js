$(function() {
	init_split();
	getFiles();
	$("#lockFilesBtn").on('click', function(event) {
		lockFiles();
	});
});

function lockFiles() {
	var proj = $("#projects").val() || "";
	if(proj){
		// var paths = [
		//   'RootFolder/FolderA/FolderB/FileA.html',
		//   'RootFolder/FolderA/FolderB/FileB.js',
		//   'RootFolder/FolderA/FolderB/FolderC/FileA',
		//   'RootFolder/FolderA/FolderB/FolderC/FileB'
		// ]

		// var obj = {}
		// paths.forEach(function(path) {
		//   path.split('/').reduce(function(r, e) {
		//     return r[e] || (e.indexOf(".")==-1?(r[e] = {}):r[e]="")
		//   }, obj)
		// })

		// console.log(obj)
	}
	else{
		
	}
}

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
			$("#fileSidebar .loading, #projectsDiv .loading").addClass('d-flex');
			$("#projectsDiv .msg, #projectsDiv #projects").addClass('d-none');
		}
	})
	.done(function(RES) {
		// Insert Projects
		var projHtml="";
		$.each(RES, function(key) {
			projHtml += '<option value="'+key+'">'+key+'</option>'
		});
		if(projHtml){
			$("#projectsDiv #projects").html(projHtml).show();
			$("#projectsDiv .msg").hide();
		}
		else{
			$("#projectsDiv #projects").hide();
			$("#projectsDiv .msg").html("No Project Found").show();
		}


		// filesHtml("#fileSidebar", RES);
		$("#fileSidebar .fileExplorer").html("Result is loaded in console");
	})
	.fail(function(jqXHR, textStatus, errorThrown) {
		console.error(jqXHR, textStatus, errorThrown);
		$("#projectsDiv #projects").hide();
		$("#projectsDiv .msg").html("Error while loading project").show();
		$("#fileSidebar .fileExplorer").html("Error Loading Data");
	})
	.always(function() {
		$("#fileSidebar .loading, #projectsDiv .loading").removeClass('d-flex');
		$("#projectsDiv .msg, #projectsDiv #projects").removeClass('d-none');
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