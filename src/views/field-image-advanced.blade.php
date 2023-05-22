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
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
							<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
						</svg>
						Choisir...
					</button>
					<a ng-href="@{{media.filepath}}" download="@{{media.filename}}" class="btn btn-secondary btn-block" ng-if="media">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" style="width: 18px">
							<path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
						</svg>
						Télécharger
					</a>
					<button type="button" class="btn btn-danger btn-block" ng-click="deleteMedia(media.id)" ng-if="media">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
							<path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
						</svg>
						Supprimer
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