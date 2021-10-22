(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var createElement = wp.element.createElement;
    var InnerBlocks = wp.editor.InnerBlocks;
    var defaultBlockProps = {
        src: window.frcaptcha_coblocks_settings.preview,
        title: 'This form is protected by FriendlyCaptcha. The Widget will be rendered here. Remark: If you have multiple Coblocks forms on this page, this the widget needs to be present on all blocks',
        style: {width: 282, height: 68}
    };


    var edit = function (props) {
        var blockProps = wp.blockEditor.useBlockProps( defaultBlockProps );

        return createElement(
            'img',
            blockProps,
        );
    }

    var save = function () {
        return wp.element.createElement(
            'img',
            defaultBlockProps,
        );
    }

    wp.blocks.registerBlockType('frcaptcha/field-friendly-captcha', {
            "apiVersion": 2,
            "name": "frcaptcha/field-friendly-captcha",
            "title": "Friendly Captcha",
            "category": "design",
            "icon": "shield-alt",
            "parent": [
                "coblocks/form"
            ],
            "description": "Inserts FriendlyCaptcha widget",
            "keywords": [
                "captcha"
            ],
            "example": {},
            "editor_script": 'frcaptcha-coblocks-edit-script',
            "editor_style": 'frcaptcha-coblocks-edit-style',
            "edit": edit,
            "save": save
        }
    );
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);