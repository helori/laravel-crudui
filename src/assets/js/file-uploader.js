angular.module('crudui').directive('fileUploader', ['$http', '$sce', '$timeout', function($http, $sce, $timeout)
{
    return{
        restrict: 'EA',
        scope: true,
        link: function(scope, elt, attrs)
        { 
            scope.media = null;
            scope.uploading = false;
            scope.upload_progress = 0;
            scope.upload_total = 0;
            scope.decache = new Date().getTime();
            scope.saving_position = false;

            // -------------------------------------------------------
            //  File input 
            // -------------------------------------------------------
            scope.input = elt.find('.file-input');
            console.log(scope.input);
            scope.input.change(function(){
                scope.file = this.files[0];
                scope.title = scope.file.name.replace(/\.[^/.]+$/, "");
                console.log(scope.file);
                scope.$apply();
                scope.upload();
            });

            // -------------------------------------------------------
            //  Get single medias
            // -------------------------------------------------------
            if(!attrs.multiple)
            {
                $http.get(attrs.routeUrl + '/media/' + attrs.itemId + '/' + attrs.collection).then(function(r){
                    scope.media = r.data;
                });
            }

            // -------------------------------------------------------
            //  Get multiple medias
            // -------------------------------------------------------
            if(attrs.multiple)
            {
                scope.medias = {};
                $http.get(attrs.routeUrl + '/medias/' + attrs.itemId + '/' + attrs.collection).then(function(r){
                    scope.medias = r.data;
                });
            }

            // -------------------------------------------------------
            //  Multiple medias ordering
            // -------------------------------------------------------
            var medias = elt.find('.medias > .row');
            medias.disableSelection().sortable({
                update: function( event, ui ){
                    scope.saving_position = true;
                    $http.post(attrs.routeUrl + '/update-medias-position', {id: attrs.itemId, mediaId: ui.item.attr('media-id'), position: ui.item.index()}).then(function(r){
                        scope.saving_position = false;
                        //console.log(r);
                    }, function(r){
                        scope.saving_position = false;
                    });
                }
            });

            // -------------------------------------------------------
            //  Upload
            // -------------------------------------------------------
            scope.upload = function()
            {
                scope.uploading = true;
                scope.upload_progress = 0;
                scope.upload_total = 0;

                var data = {
                    id: attrs.itemId,
                    collection: attrs.collection,
                    mime: scope.file.type,
                    title: scope.title
                };

                $http({
                    method: 'POST',
                    url: attrs.routeUrl + (attrs.multiple ? '/upload-medias' : '/upload-media'),
                    headers: {
                        'Content-Type': undefined, // Manually setting ‘Content-Type’: multipart/form-data will fail to fill in the boundary parameter of the request.
                        '__XHR__': function(){
                            return function(xhr) {
                                xhr.upload.addEventListener("progress", function(event) {
                                    //console.log("upload progress " + ((event.loaded/event.total) * 100) + "%", xhr, event);
                                    scope.upload_progress = event.loaded;
                                    scope.upload_total = event.total;
                                    scope.$apply();
                                });
                            }
                        }
                    },
                    data: data,
                    transformRequest: function (data, headersGetter) {
                        var formData = new FormData();
                        angular.forEach(data, function (value, key) {
                            formData.append(key, value);
                        }); 
                        formData.append(attrs.collection, scope.file, scope.file.name);
                        return formData;
                    }
                })
                .then(function (r) {
                    console.log('upload completed', r.data);
                    scope.uploading = false;
                    if(attrs.multiple)
                        scope.medias = r.data;
                    else
                        scope.media = r.data;
                    scope.decache = new Date().getTime();
                    scope.input[0].value = "";
                }, function (r) {
                    console.log('upload error', r);
                    scope.uploading = false;
                    scope.input[0].value = "";
                });
            }

            // -------------------------------------------------------
            //  Delete
            // -------------------------------------------------------
            scope.deleteMedia = function(mediaId)
            {
                $http.post(attrs.routeUrl + '/delete-media', {id: attrs.itemId, mediaId: mediaId}).then(function(r){
                    if(attrs.multiple)
                        scope.medias = r.data;
                    else
                        scope.media = null;
                    scope.decache = new Date().getTime();
                });
            }

            // -------------------------------------------------------
            //  Rename
            // -------------------------------------------------------
            scope.renameMedia = function(media, title)
            {
                $http.post(attrs.routeUrl + '/rename-media', {id: attrs.itemId, mediaId: media.id, title: title}).then(function(r){
                    angular.copy(r.data, media);
                    scope.decache = new Date().getTime();
                });
            }
        }
    }
}]);
