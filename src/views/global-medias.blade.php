@extends($layout_view)
@section('content')   

<div id="global-medias" ng-controller="GlobalMediasController" ng-init="init('{{ $route_url }}')">
    <div class="container">

        <h1>Gestion des médias</h1>

        <div class="row">
            <div class="col-xs-6">
                <div class="file-input-wrapper">
				    <input type="file" id="global-medias-file-input" />
				    <label for="global-medias-file-input" class="btn btn-primary">
				        <i class="fa fa-upload"></i> Nouveau fichier...
				    </label>
				</div>
                <button type="button" class="btn btn-success" ng-click="upload($event)" ng-disabled="!files">
                    <i class="fa fa-send"></i> Envoyer
                </button>
            </div>
            <!--div class="col-xs-6">
                <input type="text" ng-model="filters.title" class="form-control" placeholder="Rechercher..."><br>
            </div-->
        </div>

        <div class="progress">
            <div class="progress-bar" role="progressbar" ng-style="{ 'width': (100 * upload_progress / upload_total) + '%' }" style="width: 0%;"></div>
        </div>

        <div ng-repeat="it in items track by $index" class="media-upload">
            <div class="media">
                <div class="media-left">
                    <a ng-href="@{{it.filepath}}" target="_blank">
                        <div class="image">
                            <img  class="media-object"
                                  ng-src="@{{it.filepath + '?' + decache}}"
                                 ng-if="it.filepath != '' && (it.mime == 'image/png' || it.mime == 'image/jpeg')" />
                            <div ng-if="it.mime == 'application/pdf'">PDF</div>
                        </div>
                    </a>
                </div>
                <div class="media-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="file-path">Chemin : @{{it.filepath}}</div>
                            <div class="file-type">Type : @{{it.mime}}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-right">
                                <!--button type="button" class="btn btn-warning btn-xs" ng-click="item.cancel()" ng-disabled="!item.isUploading" disabled="disabled">
                                    <span class="glyphicon glyphicon-ban-circle"></span>
                                </button-->
                                <button type="button" class="btn btn-info" ng-click="download(it.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" style="width: 18px">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    Récupérer
                                </button>
                                <button type="button" class="btn btn-danger" ng-click="delete(it.id)">
                                    <i class="fa fa-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="text" class="form-control"
                           placeholder="Nom du fichier"
                           ng-model="it.title"
                           ng-change="update(it.id, it.title)"
                           ng-model-options="{updateOn: 'default blur', debounce: {'default': 500, 'blur': 0}}">
                    <!--div ng-bind="item.file.name"></div-->
                </div>
            </div>
        </div>

    </div>
</div>
@endsection