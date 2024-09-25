# V1
V1_VERSION="0.9.18"
curl -o ./friendly-captcha/public/vendor/v1/widget.module.min.js https://cdn.jsdelivr.net/npm/friendly-challenge@${V1_VERSION}/widget.module.min.js
curl -o ./friendly-captcha/public/vendor/v1/widget.polyfilled.min.js https://cdn.jsdelivr.net/npm/friendly-challenge@${V1_VERSION}/widget.polyfilled.min.js

# V2
V2_VERSION="0.1.8"
curl -o ./friendly-captcha/public/vendor/v2/site.min.js https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@${V2_VERSION}/site.min.js
curl -o ./friendly-captcha/public/vendor/v2/site.compat.min.js https://cdn.jsdelivr.net/npm/@friendlycaptcha/sdk@${V2_VERSION}/site.compat.min.js