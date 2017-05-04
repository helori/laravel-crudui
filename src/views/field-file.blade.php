<file-uploader route-url="{{ $medias_url }}" item-id="{{ $fieldData['id'] }}" collection="{{ $field['name'] }}">

    <div class="uploader-wrapper">
        <div class="media">
            
            <div class="thumb">
                <div class="image" ng-cloak
                    ng-if="media && media.mime.indexOf('image') !== -1"
                    style="background-image: url(@{{ media.filepath + '?' + decache | trustedUrl }})">
                </div>
                <video controls ng-if="media && media.mime.indexOf('video') !== -1" ng-cloak>
                    <source ng-src="@{{ media.filepath + '?' + decache | trustedUrl }}" type="video/mp4" />
                </video>
                <div class="text-wrapper" ng-if="media && media.mime.indexOf('image') === -1 && media.mime.indexOf('video') === -1" ng-cloak>
                    <div class="text">
                        <div>@{{media.mime}}</div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="inputs">
                    <div class="progress-wrapper" ng-show="uploading" ng-cloak>
                        <div class="progress-cell">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: @{{100 * upload_progress / upload_total}}%;">
                                    <span class="sr-only">@{{100 * upload_progress / upload_total}}% Complete</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div ng-show="!media && !uploading">
                        <input type="file" id="file-input-{{ $field['name'] }}-{{ $fieldData['id'] }}" class="file-input" ng-show="!media">
                        <label for="file-input-{{ $field['name'] }}-{{ $fieldData['id'] }}" class="btn btn-primary" ng-show="!media">
                            <i class="fa fa-plus"></i> Choisir un fichier...
                        </label>
                        <span class="error" ng-if="error" ng-cloak>
                            <i class="fa fa-warning"></i> @{{ error.statusText }}
                        </span>
                    </div>
                    <div class="inputs-left" ng-show="media" ng-cloak>
                        <a ng-href="@{{media.filepath}}" 
                            download="@{{media.filename}}" 
                            class="btn btn-default icon-only"
                            title="Récupérer le fichier">
                            <i class="fa fa-download"></i>
                        </a>
                        <a ng-href="@{{media.filepath}}" 
                            target="_blank"
                            class="btn btn-default icon-only"
                            title="Ouvrir le fichier">
                            <i class="fa fa-eye"></i>
                        </a>
                        <button type="button" 
                            ng-if="media && media.mime.indexOf('image') !== -1"
                            class="btn btn-default icon-only" 
                            title="Compresser le fichier"
                            ng-click="optimizeMedia(media)">
                            <i class="fa fa-file-zip-o"></i>
                        </button>
                        <button type="button" 
                            class="btn btn-danger icon-only" 
                            title="Supprimer le fichier"
                            ng-click="deleteMedia(media.id)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="inputs-right" ng-show="media" ng-cloak>
                        <input type="text" 
                            ng-model="media.title" 
                            ng-change="renameMedia(media, media.title)"
                            ng-model-options="{ updateOn: 'default blur', debounce: { 'default': 300, 'blur': 0 } }"
                            class="form-control" 
                            placeholder="Nom du fichier...">
                    </div>
                </div>
                <div class="desc" ng-show="media" ng-cloak>
                    <span>@{{media.mime}}</span>
                    <span>| @{{media.size / 1000 | number:0}} ko</span>
                    <span ng-if="media && media.mime.indexOf('image') !== -1">| @{{media.width}} x @{{media.height}} px</span>
                    <span ng-if="false">| position : @{{media.position}}</span>
                    <span>| @{{media.filename}}</span>
                </div>
            </div>

             
        </div>
    </div>

</file-uploader>