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
										<i class="fa fa-download"></i>
									</a>
								</div>
								<div class="col col-xs-6">
									<button type="button" class="btn btn-danger btn-block icon-only" ng-click="deleteMedia(m.id)">
										<i class="fa fa-trash"></i>
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