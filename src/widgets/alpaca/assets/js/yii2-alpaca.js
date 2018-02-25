var alpacaFormWasChanged = [];

var processAlpacaOptions = function() {
    var $input = $(this.control);
    var $container = $input.parent();
    if ($container.find('[data-browse]').length == 0) {
        var inputId = $input.attr("id");
        var browseId = inputId + "_browse_btn";
        var browseText = $input.data('melonfilefield_browse');
        var managerUrl = $input.data("melonfile_browser_url");
        $container.append('<button class="btn btn-default btn-sm" type="button" id="' + browseId + '" data-browse>' + browseText + '</button>');
        mihaildev.elFinder.register(inputId, function(file, id){ $('#' + id).val(file.url).trigger('change', [file, id]); return true;}); $(document).on('click', '#' + browseId, function(){mihaildev.elFinder.openManager({"id": inputId, "url": managerUrl + "&callback=" + inputId, "width": 'auto', "height": 'auto'});});
    }
};

var triggerAlpacaFormChange = function(id) {
    alpacaFormWasChanged[id] = true;
};

var checkAlpacaLanguageTabs = function(selector, id) {
    $(document).on('click', selector, function(e) {
        var linkElement = $(e.target);
        if (alpacaFormWasChanged[id] === true) {
            message = 'Do you really want leave page? All unsaved changes will be loosed.';
            if (!confirm(message)) {
                e.preventDefault();
            }
        }
    });
};