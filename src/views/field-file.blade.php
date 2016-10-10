<file-uploader route-url="{{ $medias_url }}" item-id="{{ $fieldData['id'] }}" collection="{{ $field['name'] }}">

    <div class="uploader-wrapper" ng-cloak>

        <div class="row narrow">
            <div class="col col-sm-12 col-md-4">
                
                <div class="thumb">
                    <div class="thumb-inside">

                        <div class="image" 
                            ng-if="media && media.mime.indexOf('image') !== -1"
                            style="background-image: url(@{{ media.filepath + '?' + decache | trustedUrl }})">
                        </div>
                        
                        <video controls ng-if="media && media.mime.indexOf('video') !== -1">
                            <source ng-src="@{{ media.filepath + '?' + decache | trustedUrl }}" type="video/mp4" />
                        </video>

                        <iframe ng-src="@{{ media.filepath + '?' + decache | trustedUrl }}" ng-if="media && media.mime.indexOf('pdf') !== -1"></iframe>
                        
                        <div class="progress-wrapper" ng-show="uploading">
                            <div class="progress-cell">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: @{{100 * upload_progress / upload_total}}%;">
                                        <span class="sr-only">@{{100 * upload_progress / upload_total}}% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-wrapper" ng-if="media && media.mime.indexOf('image') === -1 && media.mime.indexOf('video') === -1 && media.mime.indexOf('pdf') === -1">
                            <div class="text">
                                <div>Aucun aperçu</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col col-sm-6 col-md-4">
                <div class="inputs">
                    <input type="text" 
                        ng-model="media.title" 
                        ng-change="renameMedia(media, media.title)"
                        ng-model-options="{ updateOn: 'default blur', debounce: { 'default': 300, 'blur': 0 } }"
                        class="form-control" 
                        placeholder="Nom du fichier..." 
                        ng-show="media">
                    <input type="file" id="file-input-{{ $field['name'] }}" class="file-input" ng-show="!media">
                    <label for="file-input-{{ $field['name'] }}" class="btn btn-default btn-block" ng-show="!media">
                        <i class="fa fa-file-image-o"></i> Choisir un fichier...
                    </label>
                    <a ng-href="@{{media.filepath}}" download="@{{media.filename}}" class="btn btn-default btn-block" ng-if="media">
                        <i class="fa fa-download"></i> Télécharger
                    </a>
                    <button type="button" class="btn btn-danger btn-block" ng-click="deleteMedia(media.id)" ng-if="media">
                        <i class="fa fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
            <div class="col col-sm-6 col-md-4">
                <div class="infos" ng-if="media">
                    <div>Filename : <span>@{{media.filename}}</span></div>
                    <div>Mime : <span>@{{media.mime}}</span></div>
                    <div>Size : <span>@{{media.size / 1000 | number:0}} ko</span></div>
                    <div ng-if="media.mime.indexOf('image') !== -1">Dimensions : @{{media.width}} x @{{media.height}} px</div>
                </div>
            </div>
        </div>

    </div>

</file-uploader>