<file-uploader route-url="{{ $medias_url }}" item-id="{{ $fieldData['id'] }}" collection="{{ $field['name'] }}" multiple="true">

    <div class="uploader-wrapper">

        <div ng-show="!uploading">
            <input type="file" id="file-input-{{ $field['name'] }}-{{ $fieldData['id'] }}" class="file-input" ng-show="!media" multiple>
            <label for="file-input-{{ $field['name'] }}-{{ $fieldData['id'] }}" class="btn btn-primary" ng-show="!media">
                <i class="fa fa-plus"></i> Choisir un fichier...
            </label>
            <div class="loading" ng-if="!loaded || saving_position">
                <i class="fa fa-spinner fa-spin fa-pulse"></i>
            </div>
            <span class="error" ng-if="error" ng-cloak>
                <i class="fa fa-warning"></i> @{{ error.statusText }}
            </span>
        </div>

        <div class="progress-wrapper" ng-show="uploading" ng-cloak>
            <div class="progress-cell">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: @{{100 * upload_progress / upload_total}}%;">
                        <span class="sr-only">@{{100 * upload_progress / upload_total}}% Complete</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="medias" ng-cloak>
            <div class="media" ng-repeat="m in medias" media-id="@{{m.id}}">
                <div class="thumb">
                    <div class="image" 
                        ng-if="m && m.mime.indexOf('image') !== -1"
                        style="background-image: url(@{{ m.filepath + '?' + decache | trustedUrl }})">
                    </div>
                    <video controls ng-if="m && m.mime.indexOf('video') !== -1">
                        <source ng-src="@{{ m.filepath + '?' + decache | trustedUrl }}" type="video/mp4" />
                    </video>
                    <div class="text-wrapper" ng-if="m && m.mime.indexOf('image') === -1 && m.mime.indexOf('video') === -1">
                        <div class="text">
                            <div>@{{m.mime}}</div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="inputs">
                        <div class="inputs-left">
                            <a ng-href="@{{m.filepath}}" 
                                download="@{{m.filename}}" 
                                class="btn btn-default icon-only"
                                data-toggle="tooltip" 
                                data-placement="top" 
                                title="Récupérer le fichier">
                                <i class="fa fa-download"></i>
                            </a>
                            <a ng-href="@{{m.filepath}}" 
                                target="_blank"
                                class="btn btn-default icon-only"
                                title="Ouvrir le fichier">
                                <i class="fa fa-eye"></i>
                            </a>
                            <button type="button" 
                                class="btn btn-danger icon-only" 
                                data-toggle="tooltip" 
                                data-placement="top" 
                                title="Supprimer le fichier"
                                ng-click="deleteMedia(m.id)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        <div class="inputs-right">
                            <input type="text" 
                                ng-model="m.title" 
                                ng-change="renameMedia(m, m.title)"
                                ng-model-options="{ updateOn: 'default blur', debounce: { 'default': 300, 'blur': 0 } }"
                                class="form-control" 
                                placeholder="Nom du fichier...">
                        </div>
                    </div>
                    <div class="desc">
                        <span>@{{m.mime}}</span>
                        <span>| @{{m.size / 1000 | number:0}} ko</span>
                        <span ng-if="m && m.mime.indexOf('image') !== -1">| @{{m.width}} x @{{m.height}} px</span>
                        <span ng-if="false">| position : @{{m.position}}</span>
                    </div>
                </div>
            </div>
        </div>

        @include('laravel-crudui::uploader')

    </div>

</file-uploader>
