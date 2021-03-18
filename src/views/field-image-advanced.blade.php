<uploader route-url="{{ $medias_url }}" item-id="{{ $fieldData['id'] }}" collection="{{ $field['name'] }}">

	<div class="uploader-wrapper">

		<div class="row">
			<div class="col-sm-12 col-md-4">
				<div class="thumb">
					<div class="thumb-inside">
						<div class="image" style="background-image: url(@{{ media.filepath + '?' + decache | trustedUrl }})"></div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="actions">
					<button type="button" class="btn btn-secondary btn-block" ng-click="openImageUploader('{{ $field['name'] }}')">
						<i class="fa fa-file-image-o"></i> Choisir...
					</button>
					<a ng-href="@{{media.filepath}}" download="@{{media.filename}}" class="btn btn-secondary btn-block" ng-if="media">
						<i class="fa fa-download"></i> Télécharger
					</a>
					<button type="button" class="btn btn-danger btn-block" ng-click="deleteMedia(media.id)" ng-if="media">
						<i class="fa fa-trash"></i> Supprimer
					</button>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="infos" ng-if="media">
					<div>Mime : @{{media.mime}}</div>
					<div>Dimensions : @{{media.width}}x@{{media.height}}px</div>
					<div>Size : @{{media.size / 1000}}ko</div>
				</div>
			</div>
		</div>

		@include('laravel-crudui::uploader')

	</div>

</uploader>