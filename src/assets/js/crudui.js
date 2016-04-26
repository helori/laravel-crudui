var crudui = angular.module('crudui', [
    'medias'
]);

// ----------------------------------------------------------------------------------------------------
//		Module Config (config providers here)
// ----------------------------------------------------------------------------------------------------
crudui.config(['$locationProvider', '$httpProvider', function($locationProvider, $httpProvider)
{
    // By default, Angular sends post params as a json object in the query body.
    // It can be retreived using file_get_contents('php://input').
    // But this will be deprecated as PHP 5.6, so we prefer transforming angular request to x-www-form-urlencoded
    // This way, params can be retrieved using $_POST
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
    $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
    $httpProvider.defaults.transformRequest = function(data){
        if (data === undefined) {
            return data;
        }
        return $.param(data);
    };
}]);

crudui.directive('sortable', ['$http', function($http){
    return{
        compile: function(element, attrs){
            return function(scope, element, attrs){
                scope.savingPosition = false;
                element.find("tbody").disableSelection().sortable({
                    update: function( event, ui ){
                        scope.savingPosition = true;
                        //console.log(ui.item.attr('update-url'));
                        $http.post(ui.item.attr('update-url'), {id: ui.item.attr('id'), position: ui.item.index()}).then(function(r){
                            scope.savingPosition = false;
                            console.log(r);
                        }, function(r){
                            console.log(r);
                        });
                    }
                });
            };
        }
    };
}]);

crudui.directive('listCheckbox', ['$http', function($http){
    return{
        restrict: 'A',
        link: function(scope, elt, attrs){
            var data = {
                id: attrs.itemId,
                type: attrs.fieldType,
                name: attrs.fieldName
            }
            var url = attrs.updateUrl;
            elt.change(function(){
                data.value = $(this).is(':checked');
                $http.post(url, data).then(function(r){
                    //console.log(r);
                });
            });
        }
    };
}]);

crudui.controller('CrudListController', ['$scope', '$http', function($scope, $http)
{
    $scope.openCreateDialog = function(e){
        e.preventDefault();
        $("#create-dialog").modal('show');
    };
}]);

crudui.controller('JsonController', ['$scope', function($scope)
{
    $scope.items = [];
    $scope.fieldname = 'fieldname';

    $scope.initItems = function(fieldname, items){
        $scope.fieldname = fieldname;
        $scope.items = JSON.parse(items);
        if(!angular.isArray($scope.items))
            $scope.items = [];
        $scope.updateItems();
    };
    $scope.addItem = function(){
        $scope.items.push({});
    }
    $scope.updateItems = function(){
        $("#" + $scope.fieldname).val(JSON.stringify($scope.items));
    }
    $scope.removeItem = function(item){
        var idx = $scope.items.indexOf(item);
        if(idx !== -1){
            $scope.items.splice(idx, 1);
            $scope.updateItems();
        }
    }
}]);

crudui.directive('multicheck', ['$interval', '$filter', function($interval, $filter){
    return{
        restrict: 'E',
        scope: {},
        template: '<div ng-repeat="opt in opts"><label id=""><input type="checkbox" ng-true-value="1" ng-false-value="0" ng-model="opt.checked" ng-change="update()"> {{opt.label}}</label></div><input type="hidden" name="{{name}}" ng-value="value">',
        link: function(scope, elt, attrs){
            scope.name = attrs.name;
            scope.options = JSON.parse(attrs.options);
            scope.value = attrs.value;
            if(!scope.value)
                scope.value = '[]';
            scope.model = JSON.parse(scope.value);

            scope.opts = [];
            angular.forEach(scope.options, function(v, k){
                scope.opts.push({
                    value: k,
                    label: v,
                    checked: (scope.value.indexOf(k) === -1) ? 0 : 1
                });
            });

            console.log(scope.opts);

            scope.update = function(){
                scope.model = [];
                angular.forEach(scope.opts, function(opt){
                    if(opt.checked){
                        scope.model.push(opt.value);
                    }
                });
                scope.value = JSON.stringify(scope.model);
            };
        }
    };
}]);


crudui.directive('tinymce', ['$http', function($http)
{
    return{
        restrict: 'A',
        replace: true,
        link: function(scope, element, attrs)
        {
            console.log("textarea#" + attrs.id);

            tinyMCE.baseURL = 'tinymce';
            console.log('TinyMCE base url : ' + tinyMCE.baseURL);

            // ----------------------------------------------------
            //  TinyMCE images and links from "medias" table
            // ----------------------------------------------------
            //$http.post(apiUrl + "/tiny-mce-medias").then(function(d){
                //console.log('medias', d.data);

                var medias = [];
                /*angular.forEach(d.data, function(media){
                    medias.push({
                        title: media.name + ' (' + utils.mimeTypeToText(media.fileType) + ')',
                        value: media.filePath
                    });
                });*/
                var tinyMceImages = medias;
                var tinyMceLinks = medias;

                // ----------------------------------------------------
                //  Options globales pour TinyMCE
                // ----------------------------------------------------
                scope.tinyMCE_options = {
                    selector: "textarea#" + attrs.id,
                    height: 300,
                    resize: "vertical",
                    language : 'fr_FR',
                    theme: "modern",
                    body_class: "tinymce-body",
                    content_css : "/css/tinymce.css",
                    document_base_url: '/', //"<?php echo HTTP_HOST.$this->baseUrl(); ?>",
                    relative_urls: true,
                    convert_urls: false,
                    remove_script_host: true,
                    schema: "html5",
                    inline: false,
                    statusbar: false,
                    forced_root_block: false, // 'p'
                    //media_filter_html: true,
                    //extended_valid_elements:"iframe[src|title|width|height|allowfullscreen|frameborder|class|id],object[classid|width|height|codebase|*],param[name|value|_value|*],embed[type|width|height|src|*]",
                    //extended_valid_elements : "iframe[src|width|height|name|align|allowfullscreen|frameborder]",
                    //fontsize_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 18px 20px 22px 24px 26px 28px 30px 36px 42px",
                    //image_list: tinyMceImages,
                    //link_list: tinyMceLinks,
                    plugins: [
                        "textcolor advlist autolink lists link image charmap print preview anchor emoticons", // media
                        "searchreplace visualblocks code fullscreen charmap",
                        "insertdatetime table contextmenu paste" //moxiemanager
                    ],
                    //menubar: "tools table format view insert edit",
                    menubar: false,
                    //toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                    toolbar: "undo redo | bold italic | fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | code",
                    //toolbar2: "fontsizeselect | forecolor backcolor | charmap | emoticons | media",
                    /*style_formats: [
                     {title: 'Bold text', inline: 'b'},
                     {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
                     {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
                     {title: 'Example 1', inline: 'span', classes: 'example1'},
                     {title: 'Example 2', inline: 'span', classes: 'example2'},
                     {title: 'Table styles'},
                     {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                     ],*/
                    setup: function(editor) {
                        editor.on('init', function(e) {
                            console.log('Editor initialized', e);
                            /*if(scope.model){
                                editor.setContent(scope.model, {format : "raw"});
                                console.log("Editor filled", scope.model);
                            }*/
                        });
                    }
                };
                //angular.extend(scope.tinyMCE_options, scope.options);
                tinymce.init(scope.tinyMCE_options);
            //});
        }
    };
}]);

