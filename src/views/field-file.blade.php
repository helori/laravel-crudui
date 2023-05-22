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
                        <label for="file-input-{{ $field['name'] }}-{{ $fieldData['id'] }}" ng-show="!media" class="filedrop">
                            <h2>Charger de nouveaux fichiers</h2>
                            <p>Glisser-déposer les fichiers (ou cliquer) dans cette zone</p>
                        </label>
                        <span class="error" ng-if="error" ng-cloak>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>                          
                            @{{ error.statusText }}
                        </span>
                    </div>
                    <div class="inputs-left" ng-show="media" ng-cloak>
                        <a ng-href="@{{media.filepath}}" 
                            download="@{{media.filename}}" 
                            class="btn btn-secondary icon-only"
                            title="Récupérer le fichier">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" style="width: 18px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </a>
                        <a ng-href="@{{media.filepath}}" 
                            target="_blank"
                            class="btn btn-secondary icon-only"
                            title="Ouvrir le fichier">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>                              
                        </a>
                        <button type="button" 
                            ng-if="media && media.mime.indexOf('image') !== -1"
                            class="btn btn-secondary icon-only" 
                            title="Compresser le fichier"
                            ng-click="optimizeMedia(media)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                            </svg>
                        </button>
                        <button type="button" 
                            class="btn btn-danger icon-only" 
                            title="Supprimer le fichier"
                            ng-click="deleteMedia(media.id)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
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