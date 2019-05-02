let mix = require("laravel-mix");
let distPath = "public/build/frontend";
let outputPath = "/build/frontend/";

mix
  .combine(
    [
      "resources/assets/frontend/css/bonofide/bootstrap.min.css",
      "node_modules/font-awesome/css/font-awesome.min.css",
      "resources/assets/frontend/css/bonofide/animate.min.css",
      "resources/assets/frontend/css/bonofide/normalize.css",
      "resources/assets/frontend/css/bonofide/owl.carousel.min.css",
      "resources/assets/frontend/css/bonofide/owl.transitions.css",
      "resources/assets/frontend/css/bonofide/magnific-popup.css",
      "resources/assets/frontend/css/bonofide/style.css",
      "resources/assets/frontend/css/bonofide/responsive.css"
    ],
    distPath + "/css/bundle.css"
  )
  .babel(
    [
      "node_modules/jquery/dist/jquery.min.js",
      "resources/assets/frontend/js/bonofide/bootstrap.min.js",
      "resources/assets/frontend/js/bonofide/owl.carousel.min.js",
      "resources/assets/frontend/js/bonofide/jquery.mixitup.js",
      "resources/assets/frontend/js/bonofide/jquery.magnific-popup.min.js",
      "resources/assets/frontend/js/bonofide/jquery.waypoints.min.js",
      "resources/assets/frontend/js/bonofide/jquery.ajaxchimp.min.js",
      "resources/assets/frontend/js/bonofide/main_script.js",
      "resources/assets/frontend/js/bonofide/index.js"
    ],
    distPath + "/js/bundle.js"
  )
  .copy("node_modules/font-awesome/fonts", distPath + "/fonts")
  .copy("resources/assets/frontend/images", distPath + "/images")
  .disableNotifications()
  .setPublicPath(distPath);

let config = {
  output: {
    publicPath: outputPath,
    chunkFilename: "js/[name].js?[chunkhash]"
  }
};
mix.webpackConfig(config);

if (mix.inProduction()) {
  mix.version();
}
