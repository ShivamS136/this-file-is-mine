<?php
	require_once(getcwd()."/resources/config.php");
	require_once(DOCUMENT_ROOT."/modules/view/header.php");
?>
	<div class="container-fluid p-3">
		<div class="row m-0 py-3 bg-light mb-3">
			<div class="col-12 col-sm-3 border-right">
				<div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
					<a class="nav-link active" id="lockFilesTab" data-toggle="pill" href="#lockFiles" role="tab" aria-controls="lockFiles" aria-selected="true">Lock Files</a>
					<a class="nav-link" id="releaseFilesTab" data-toggle="pill" href="#releaseFiles" role="tab" aria-controls="releaseFiles" aria-selected="false">Release Files</a>
					<a class="nav-link" id="getFilesStatusTab" data-toggle="pill" href="#getFilesStatus" role="tab" aria-controls="getFilesStatus" aria-selected="false">Get Files' Status</a> 
				</div>  
			</div>
			<div class="col-12 col-sm-9">
				<div class="tab-content">
					<div id="formProject">
						<div class="form-group row">
							<label for="projects" class="col-sm-3 col-form-label text-sm-right">Project:</label>
							<div class="col-sm-9" id="projectsDiv" style="max-width:400px;">
								<select class="custom-select" id="projects"></select>
								<p class="msg text-muted d-none"></p>
								<div class="loading d-flex d-none justify-content-center align-items-center">
									<div class="spinner-border" role="status">
										<span class="sr-only">Loading Projects...</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				<div class="tab-pane fade show active" id="lockFiles" role="tabpanel" aria-labelledby="lockFilesTab">
					<div class="form-group row">
						<label for="lockFilesList" class="col-sm-3 col-form-label text-sm-right">List of files:</label>
						<div class="col-sm-9" id="lockFilesListDiv" style="max-width:400px;">
							<textarea id="lockFilesList" class="form-control" rows="3" data-gramm_editor="false" data-gramm="false" spellcheck="false"></textarea>
							<p class="msg text-muted d-none"></p>
						</div>
					</div>
					<div class="form-group row justify-content-center">
						<button class="btn btn-outline-primary" id="lockFilesBtn" data-toggle="modal" data-target="#lockFilesModal">Lock Files</button>
					</div>
				</div>
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

	<!-- Bootstrap Modals -->
	<div class="modal fade" id="lockFilesModal" tabindex="-1" aria-labelledby="lockFilesModal" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Lock Files</h5>
					<!-- <button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> -->
				</div>
				<div class="modal-body">
					<div class="loading d-flex d-none justify-content-center align-items-center">
						<div class="spinner-border" role="status">
							<span class="sr-only">Locking Files...</span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-success" data-dismiss="modal">Continue Locking</button>
					<button type="button" class="btn btn-outline-warning" data-dismiss="modal">Cancel Locking</button>
				</div>
			</div>
		</div>
	</div>
<?php
	require_once(DOCUMENT_ROOT."/modules/view/footer.php");
?>
