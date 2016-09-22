crudui.directive('fileUploader', ['$http', '$sce', '$timeout', function($http, $sce, $timeout)
{
    return{
        restrict: 'EA',
        scope: true,
        link: function(scope, elt, attrs)
        { 
            scope.media = null;
            scope.title = '';
            scope.uploading = false;
            scope.upload_progress = 0;
            scope.upload_total = 0;
            scope.decache = new Date().getTime();
            scope.trusted_url = '';
            
            // -------------------------------------------------------
            //  Récupérer le média
            // -------------------------------------------------------
            scope.media = {};
            $http.get(attrs.routeUrl + '/media/' + attrs.itemId + '/' + attrs.collection).then(function(r){
                console.log('loaded', r.data);
                scope.media = r.data;
                scope.decache = new Date().getTime();
                scope.trusted_url = $sce.trustAsResourceUrl(scope.media.filepath + '?' + scope.decache);

                // -------------------------------------------------------
                //  File input 
                // -------------------------------------------------------
                scope.input = elt.find('.file-input');
                scope.input.change(function(){
                    scope.file = this.files[0];
                    scope.title = scope.file.name.replace(/\.[^/.]+$/, "");
                    scope.$apply();
                    scope.upload();
                });
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
                    url: attrs.routeUrl + '/upload-media',
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
                    scope.media = r.data;
                    scope.decache = new Date().getTime();
                    scope.trusted_url = $sce.trustAsResourceUrl(scope.media.filepath + '?' + scope.decache);
                    scope.input[0].value = "";
                }, function (r) {
                    console.log('upload error', r);
                    scope.uploading = false;
                    scope.input[0].value = "";
                });
            }

            scope.deleteMedia = function(mediaId)
            {
                $http.post(attrs.routeUrl + '/delete-media', {id: attrs.itemId, mediaId: mediaId}).then(function(r){
                    scope.media = null;
                    scope.decache = new Date().getTime();
                    scope.trusted_url = null;
                });
            }
        }
    }
}]);
