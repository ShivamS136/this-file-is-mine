<?php
	require_once(getcwd()."/resources/config.php");
	require_once(DOCUMENT_ROOT."/modules/view/header.php");
?>
	<div class="container-fluid p-3">
		<div class="row m-0 py-3 bg-light mb-3">
			<div class="col-12 col-md-3">
				<div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
					<a class="nav-link active" id="lockFilesTab" data-toggle="pill" href="#lockFiles" role="tab" aria-controls="lockFiles" aria-selected="true">Lock Files</a>
					<a class="nav-link" id="releaseFilesTab" data-toggle="pill" href="#releaseFiles" role="tab" aria-controls="releaseFiles" aria-selected="false">Release Files</a>
					<a class="nav-link" id="getFilesStatusTab" data-toggle="pill" href="#getFilesStatus" role="tab" aria-controls="getFilesStatus" aria-selected="false">Get Files' Status</a> 
				</div>  
			</div>
			<div class="col-12 col-md-9">
				<div class="tab-content">
				<div class="tab-pane fade show active" id="lockFiles" role="tabpanel" aria-labelledby="lockFilesTab">Lock Files</div>
				<div class="tab-pane fade" id="releaseFiles" role="tabpanel" aria-labelledby="releaseFilesTab">Release Files</div>
				<div class="tab-pane fade" id="getFilesStatus" role="tabpanel" aria-labelledby="getFilesStatusTab">Get Files' Status</div>
				</div>
			</div>
		</div>
		<div class="row m-0 bg-light mb-3">
			<div class="col bg-dark text-light" id="fileSidebar">
				<div class="loading d-none d-flex justify-content-center align-items-center">
					<div class="spinner-border" role="status">
						<span class="sr-only">Loading Files...</span>
					</div>
				</div>
				<div class="fileExplorer"></div>
			</div>
			<div class="col" id="fileDetail">
				a
			</div>
		</div>
	</div>
<?php
	require_once(DOCUMENT_ROOT."/modules/view/footer.php");
?>
