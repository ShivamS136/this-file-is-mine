$(document).ready(function(){
	
});

function testDB(db_name) {
	$.ajax({
		url:	"",
		data:	{
			action:	db_name
		},
		type:	"POST",
		success:	function(RES){
			console.log(RES);
		}
	});
}
