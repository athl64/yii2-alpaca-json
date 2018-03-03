var dvixiAlpacaWidget = {
    /**
     * variable fot storing form change data per widget
     */
    alpacaFormWasChanged: [],
    /**
     * Callback used by widget to prepare options for AlpacaJs and incapsulate yii2-specific data, such as file input buttons
     */
    processAlpacaOptions: function() {
        var $input = $(this.control);
        var $container = $input.parent();
        if ($container.find('[data-browse]').length == 0) {
            var inputId = $input.attr("id");
            var browseId = inputId + "_browse_btn";
            var browseText = $input.data('melonfilefield_browse');
            var managerUrl = $input.data("melonfile_browser_url");
            $input.attr('readonly', true);
            $container.append('<button class="btn btn-default btn-sm" type="button" id="' + browseId + '" data-browse>' + browseText + '</button>').addClass('alpaca-file-field-container');
            dvixiAlpacaWidget.handleFileRemoveButton($input);
            /* ElFinder specific code */
            mihaildev.elFinder.register(inputId, function(file, id){ $('#' + id).val(file.url).trigger('change', [file, id]); dvixiAlpacaWidget.changeFilePreview(file, $container); return true;}); $(document).on('click', '#' + browseId, function(){mihaildev.elFinder.openManager({"id": inputId, "url": managerUrl + "&callback=" + inputId, "width": 'auto', "height": 'auto'});});
            dvixiAlpacaWidget.changeFilePreview({url: $input.val(), name:$input.val()}, $container);
        }
    },
    handleFileRemoveButton: function($input) {
        var $container = $input.parent();
        var deleteId = $input.attr('id') + '_delete_btn';
        if ($input.val() != '' && $input.val() != null && $input.val() != undefined) {
            if ($container.find('#' + deleteId).length == 0) {
                /* if file selected - apppend remove btn */
                $('<button class="btn btn-danger btn-sm" type="button" id="' + deleteId + '">X</button>').insertAfter($container.find('button'));
                /* clear file field event handler */
                $(document).on('click', '#' + deleteId, function(e) {
                    $input.val('');
                    dvixiAlpacaWidget.changeFilePreview({url: $input.val(), name:$input.val()}, $container);
                    $container.find('.btn-danger').remove();
                });
            }
        } else {
            $container.find('.btn-danger').remove();
        }
    },
    /**
     * Must be called after any form field changed
     * Used to remember changes and notify yser about leaving page with unsaved changes
     * @param id
     */
    triggerAlpacaFormChange: function(id) {
        dvixiAlpacaWidget.alpacaFormWasChanged[id] = true;
    },
    /**
     * Used for handling language tabs click event and prevent leaving form with unsaved changes
     * @param selector
     * @param id
     * @param message
     */
    checkAlpacaLanguageTabs: function(selector, id, message) {
        $(document).on('click', selector, function(e) {
            if (dvixiAlpacaWidget.alpacaFormWasChanged[id] === true) {
                if (!confirm(message)) {
                    e.preventDefault();
                }
            }
        });
    },
    /**
     * Adds file preview block for file container block
     * @param file
     * @param $container
     */
    changeFilePreview: function(file, $container) {
        var src = file.url;
        var previewBlock = $container.find('.file-preview-block').eq(0);
        if (previewBlock.length < 1) {
            previewBlock = $('<div>', {class: 'file-preview-block'}).appendTo($container);
        }
        if (dvixiAlpacaWidget.isImage(file.url)) {
            previewBlock.html('<img src="' + src +  '"/>');
        } else {
            previewBlock.html(file.name);
        }
    },
    /**
     * Check url for image extensions in filename
     * @param url
     * @returns {boolean}
     */
    isImage: function(url) {
        return(url.match(/\.(jpeg|jpg|gif|png|bmp)$/) != null);
    }
};