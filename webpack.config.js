var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('login', './app/web/ZenoAuth/Infrastructure/Symfony/Resources/assets/js/login.js')
    .addEntry('register', './app/web/ZenoAuth/Infrastructure/Symfony/Resources/assets/js/register.js')
    .addEntry('authorize', './app/web/ZenoAuth/Infrastructure/Symfony/Resources/assets/js/authorize.js')
    .addEntry('user-control', './app/web/ZenoAuth/Infrastructure/Symfony/Resources/assets/js/components/user-control.component.js')
    .addEntry('profile', './app/web/ZenoAuth/Infrastructure/Symfony/Resources/assets/js/profile.js')
    .addEntry('security', './app/web/ZenoAuth/Infrastructure/Symfony/Resources/assets/js/security.js')
    .addStyleEntry('web', './resources/assets/sass/web.scss')

    .createSharedEntry('vendors', [
        './node_modules/load-awesome/css/ball-fall.css'
    ])

    .enableSassLoader()
    .enableVueLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    // .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
