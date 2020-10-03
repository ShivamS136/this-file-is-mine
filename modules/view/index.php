<?php
	require_once(getcwd()."/resources/config.php");
	require_once(DOCUMENT_ROOT."/modules/view/header.php");
?>
	<div class="container-fluid p-3">
		<div class="row py-2">
			<div class="col-12 col-md-3">
				<div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
					<a class="nav-link active" id="lock-files-tab" data-toggle="pill" href="#lock-files" role="tab" aria-controls="lock-files" aria-selected="true">Lock Files</a>
					<a class="nav-link" id="release-files-tab" data-toggle="pill" href="#release-files" role="tab" aria-controls="release-files" aria-selected="false">Release Files</a>
					<a class="nav-link" id="get-files-status-tab" data-toggle="pill" href="#get-files-status" role="tab" aria-controls="get-files-status" aria-selected="false">Get Files' Status</a> 
				</div>  
			</div>
			<div class="col-12 col-md-9">
				<div class="tab-content">
				<div class="tab-pane fade show active" id="lock-files" role="tabpanel" aria-labelledby="lock-files-tab">Lock Files</div>
				<div class="tab-pane fade" id="release-files" role="tabpanel" aria-labelledby="release-files-tab">Release Files</div>
				<div class="tab-pane fade" id="get-files-status" role="tabpanel" aria-labelledby="get-files-status-tab">Get Files' Status</div>
				</div>
			</div>
		</div>
		<div class="row py-2">
			<div class="col-12 p-3">
				
			</div>
		</div>
	</div>
<?php
	require_once(DOCUMENT_ROOT."/modules/view/footer.php");
?>
