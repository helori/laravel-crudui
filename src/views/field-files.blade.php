<file-uploader route-url="{{ $medias_url }}" item-id="{{ $fieldData['id'] }}" collection="{{ $field['name'] }}" multiple="true">

    <div class="uploader-wrapper">

        <input type="file" id="file-input-{{ $field['name'] }}" class="file-input" ng-show="!media">
        <label for="file-input-{{ $field['name'] }}" class="btn btn-default btn-block" ng-show="!media">
            <i class="fa fa-file-image-o"></i> Choisir un fichier...
        </label>

        <div class="medias">
            <div class="row narrow">
                <div class="col col-sm-6 col-md-4" ng-repeat="m in medias" media-id="@{{m.id}}">
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

                        <div class="details">
                            <div class="row narrow">
                                <div class="col col-xs-6">
                                    <a ng-href="@{{m.filepath}}" 
                                        download="@{{m.filename}}" 
                                        class="btn btn-default btn-block icon-only"
                                        data-toggle="tooltip" 
                                        data-placement="top" 
                                        title="Récupérer le fichier">
                                        <i class="fa fa-download"></i>
                                    </a>
                                </div>
                                <div class="col col-xs-6">
                                    <button type="button" 
                                        class="btn btn-danger btn-block icon-only" 
                                        data-toggle="tooltip" 
                                        data-placement="top" 
                                        title="Supprimer le fichier"
                                        ng-click="deleteMedia(m.id)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="infos">
                                <div>Type : @{{m.mime}}</div>
                                <div>Poids : @{{m.size / 1000 | number:0}} ko</div>
                                <div>Taille : <span ng-if="m && m.mime.indexOf('image') !== -1">@{{m.width}} x @{{m.height}} px</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('laravel-crudui::uploader')

    </div>

</file-uploader>
