<uploader route-url="{{ $medias_url }}" item-id="{{ $fieldData['id'] }}" collection="{{ $field['name'] }}" multiple="true">

	<div class="uploader-wrapper">

		<button type="button" class="btn btn-secondary btn-block" ng-click="openImageUploader('{{ $field['name'] }}')">
			<i class="fa fa-file-image-o"></i> Choisir...
		</button>

		<div class="medias">
			<div class="row narrow">
				<div class="col col-sm-4 col-md-3" ng-repeat="m in medias" media-id="@{{m.id}}">
					<div class="media">
						
						<div class="thumb">
                                
                            <div class="image" 
                                ng-if="m && m.mime.indexOf('image') !== -1"
                                style="background-image: url(@{{ m.filepath + '?' + decache | trustedUrl }})">
                            </div>
                            
                            <video controls ng-if="m && m.mime.indexOf('video') !== -1">
                                <source ng-src="@{{ m.filepath + '?' + decache | trustedUrl }}" type="video/mp4" />
                            </video>
                            
                        </div>
						
						<div class="actions">
							<div class="row narrow">
								<div class="col col-xs-6">
									<a ng-href="@{{m.filepath}}" download="@{{m.filename}}" class="btn btn-secondary btn-block icon-only">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" style="width: 18px">
											<path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
										</svg>
									</a>
								</div>
								<div class="col col-xs-6">
									<button type="button" class="btn btn-danger btn-block icon-only" ng-click="deleteMedia(m.id)">
										<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
											<path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
										</svg>
									</button>
								</div>
							</div>
						</div>
						<div class="infos">
							<div>Mime : @{{m.mime}}</div>
							<div>Dimensions : @{{m.width}}x@{{m.height}}px</div>
							<div>Size : @{{m.size / 1000}}ko</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		@include('laravel-crudui::uploader')

	</div>

</uploader>