<div id="{{ $field['name'] }}-dialog" class="modal fade uploader-dialog" tabindex="-1" role="dialog">
    <div class="modal-dialog">
    	
    	<div class="modal-content">
    		<div class="modal-body">
				<div class="columns">

					<div class="col-left">

						<input type="file" id="file-input-{{ $field['name'] }}" class="file-input" autofocus>
						<label for="file-input-{{ $field['name'] }}" class="btn btn-default btn-block"><i class="fa fa-file-image-o"></i> Choisir un fichier...</label>

			    		<div class="preview-wrapper">
				    		<div class="preview">

								<canvas class="canvas-back"></canvas>
								<div class="cropper-overlay" ng-hide="!file"></div>
								<div class="cropper" ng-hide="!file" style="left:@{{modified.x * scale}}px;top:@{{modified.y * scale}}px;width:@{{modified.w * scale}}px;height:@{{modified.h * scale}}px;">
									<div class="loading" ng-show="loading"><i class="fa fa-cog fa-spin"></i></div>
						            <div class="progress-wrapper" ng-show="uploading">
						            	<div class="progress">
							                <div class="progress-bar" role="progressbar" style="width: @{{100 * upload_progress / upload_total}}%;">
							                    <span class="sr-only">@{{100 * upload_progress / upload_total}}% Complete</span>
							                </div>
							            </div>
						            </div>
									<canvas class="canvas-cropped" ng-show="file" style="left:@{{-modified.x * scale}}px;top:@{{-modified.y * scale}}px"></canvas>
									<div class="grab top-left"></div>
									<div class="grab top-right"></div>
									<div class="grab bottom-left"></div>
									<div class="grab bottom-right"></div>
									<div class="grab top"></div>
									<div class="grab bottom"></div>
									<div class="grab left"></div>
									<div class="grab right"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-right">
						<ng-form name="formUpload">

						    <div class="tools" ng-show="file">
						    	
						    	<h2>Image originale</h2>
						        <div class="properties">
							        <div class="">Nom : <span ng-if="file">@{{file.name}}</span></div>
							        <div class="">Taille : <span ng-if="file">@{{file.size/1000 | number:' ':''}} ko</span></div>
							        <div class="">Dimensions : <span ng-if="file">@{{original.w}} x @{{original.h}} px</span></div>
							        <div class="">Type : <span ng-if="file">@{{file.type}}</span></div>
							    </div>

							    <h2>Image de sortie</h2>
						    	<!--div class="form-group">
							    	<div class="input-group">
							    		<span class="input-group-addon">X</span>
							    		<input type="number" name="x" id="x" min="0" max="100000000" step="1" ng-model="modified.x" ng-change="updateX()" class="form-control">
							    		<span class="input-group-addon">px</span>
							    	</div>
							    </div>
							    <div class="form-group">
							    	<div class="input-group">
							    		<span class="input-group-addon">Y</span>
							    		<input type="number" name="y" id="y" min="0" max="100000000" step="1" ng-model="modified.y" ng-change="updateY()" class="form-control">
							    		<span class="input-group-addon">px</span>
							    	</div>
							    </div>
							    <div class="form-group">
							    	<div class="input-group">
							    		<span class="input-group-addon">Largeur</span>
							    		<input type="number" name="width" id="width" min="0" max="100000000" step="1" ng-model="modified.w" ng-change="updateWidth()" class="form-control">
							    		<span class="input-group-addon">px</span>
							    	</div>
							    </div>
							    <div class="form-group">
							    	<div class="input-group">
							    		<span class="input-group-addon">Hauteur</span>
							    		<input type="number" name="height" id="height" min="0" max="100000000" step="1" ng-model="modified.h" ng-change="updateHeight()" class="form-control">
							    		<span class="input-group-addon">px</span>
							    	</div>
							    </div-->
							    
							    <div class="form-group">
						    		<input type="text" ng-model="title" class="form-control" required placeholder="Titre..." ng-disabled="!file">
						    	</div>
						    	<div class="form-group">
							    	<select class="form-control" name="format" ng-model="format" ng-change="updateImage()" ng-disabled="!file" >
							    		<option value="jpg">JPEG</option>
							    		<option value="png">PNG</option>
							    	</select>
							    </div>
							    <div class="form-group">
							    	<div class="checkbox">
							    		<label for="antialiasing">
							    			<input type="checkbox" ng-model="antialiasing" ng-true-value="true" ng-false-value="false" ng-change="updateImage();"> Antialiasing
							    		</label>
							    	</div>
							    </div>
							    <div class="form-group">
							    	<div class="checkbox">
							    		<label for="width_forced">
							    			<input type="checkbox" ng-model="width_forced" ng-true-value="true" ng-false-value="false" ng-change="updateWidthForced()"> Modifier la taille finale
							    		</label>
							    	</div>
							    </div>
							    <div class="form-group" ng-show="width_forced">
							    	<div class="input-group">
							    		<span class="input-group-addon">Largeur</span>
							    		<input type="number" name="width" min="0" max="@{{modified.w}}" step="1" class="form-control"
							    			ng-model="width"
							    			ng-change="updateWidthForced(); updateImage()"
							    			ng-disabled="!file || !width_forced" 
							    			ng-model-options="{ updateOn: 'default blur', debounce: { 'default': 800, 'blur': 0 } }">
							    		<span class="input-group-addon">px</span>
							    	</div>
							    	<div ng-messages="formUpload.width.$error" role="alert">
		                                <div class="help-block" ng-message="isValid">Ceci n'est pas un nombre.</div>
		                                <div class="help-block" ng-message="max">Maximum : @{{modified.w | number}} px</div>
		                                <div class="help-block" ng-message="max">Minimum : 0 px</div>
		                            </div>
							    </div>
							    <div class="form-group">
							    	<div>Dimensions : @{{width}} x @{{height}} px</div>
							    </div>

							    <h2>Filtres</h2>
							    <div class="form-group">
							    	<div class="checkbox">
							    		<label for="grayscale">
							    			<input type="checkbox" ng-model="grayscale" ng-true-value="true" ng-false-value="false" ng-change="updateImage();"> Noir & Blanc
							    		</label>
							    	</div>
							    </div>
							    <!--div class="form-group">
						        	<input type="number" min="-255" max="255" step="0.1" ng-model="brightness" ng-change="updateImage()" class="form-control">
						        </div-->
						    </div>

						</ng-form>
					</div>

				</div>
			</div>

			<div class="modal-footer">
				<button type="button" ng-click="updateOutput()" class="btn btn-default" ng-disabled="!file" ng-show="file && outputNeedsRefresh">
			        <i class="fa fa-refresh"></i> Générer l'image de sortie
			    </button>
				<a ng-click="download($event)" href="@{{trusted_data_url}}" download="@{{title}}.@{{format}}" class="btn btn-default" ng-disabled="!file" ng-show="!outputNeedsRefresh">
					<i class="fa fa-download"></i> Télécharger sur votre ordinateur
				</a>
				<button type="button" ng-click="upload($event)" class="btn btn-default" ng-disabled="!file" ng-show="!outputNeedsRefresh">
			        <i class="fa fa-upload"></i> Envoyer sur le site
			    </button>
				<button type="button" data-dismiss="modal" class="btn btn-default">
			        <i class="fa fa-close"></i> Fermer
			    </button>
	        </div>

		</div>

    </div>
</div>