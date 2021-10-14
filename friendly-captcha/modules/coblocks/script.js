(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

    var edit = function (props) {
        var blockProps = wp.blockEditor.useBlockProps({style: {}});

        return wp.element.createElement(
            'div',
            blockProps,
            'FriendlyCaptcha Widget will be rendered here.'
        );
    }

    var save = function () {
        var blockProps = useBlockProps.save({style: {}});

        return wp.element.createElement(
            'div',
            blockProps,
            'FriendlyCaptcha Widget will be rendered here.'
        );
    }

    wp.blocks.registerBlockType('frcaptcha/field-friendly-captcha', {
            "apiVersion": 2,
            "name": "frcaptcha/field-friendly-captcha",
            "title": "Friendly Captcha",
            "category": "design",
            "parent": [
                "coblocks/form"
            ],
            "description": "Inserts FriendlyCaptcha widget",
            "keywords": [
                "captcha",
            ],
            "example": {},
            "edit": edit,
            "save": save
        }
    );
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);