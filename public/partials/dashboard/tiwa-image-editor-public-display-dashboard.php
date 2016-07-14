<!--<div id="tiwa-imageeditor-my" class="row"></div>
<div id="tiwa-imageeditor-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">
     Modal content
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <b class="modal-title">{{image title}}</b>
      </div>
      <div class="modal-body">
		  <p><img class="modal-image"></p>
          <p class="modal-description">{{image descrpition}}</p>
      </div>
      <div class="modal-footer">
		<button type="button" class="modal-delete-button btn btn-default">Delete</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>-->
<div ng-app="starter">
	<div ng-show="!permission_error">
		<ul class="nav nav-tabs">
			<li role="presentation" ng-class="{active:$state.includes('my')}"><a ui-sref="my.images">Your Images</a></li>
			<li role="presentation" ng-class="{active:$state.includes('shared')}"><a ui-sref="shared.images">Your friend's Images</a></li>
		</ul>
		<div style="margin-top:15px;" ui-view></div>
	</div>
	<div ng-show="permission_error" class="alert alert-warning alert-dismissible" role="alert">
		You don't have permission to view this page. Please login.
	</div>
</div>
