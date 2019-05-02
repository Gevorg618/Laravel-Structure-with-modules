let mix = require("laravel-mix");
let path = require("path");
let distPath = "public/build/dashboard/";
let outputPath = "/build/dashboard/";
const VueLoaderPlugin = require("vue-loader/lib/plugin");

mix.webpackConfig({
  resolve: {
    modules: [
      path.resolve(__dirname, "../../node_modules"),
      path.resolve(__dirname, "../../modules/Dashboard/Assets/dashboard/js"),
      path.resolve(__dirname, "../../resources/common/application/js")
    ],
    alias: {
      "@": path.resolve(
        __dirname,
        "../../modules/Dashboard/Assets/dashboard/js"
      ),
      "@@": path.resolve(__dirname, "../../resources/common/application/js")
    }
  },
  plugins: [new VueLoaderPlugin()]
});

mix
  .js("modules/Dashboard/Assets/dashboard/js/main.js", distPath)
  .sass("modules/Dashboard/Assets/dashboard/sass/app.scss", distPath)
  .disableNotifications()
  .options({
    processCssUrls: false
  })
  .extract(
    [
      "vue",
      "vuex",
      "buefy",
      "element-ui",
      "lodash",
      "moment",
      "accounting",
      "vue-router",
      "vuelidate",
      "axios"
    ],
    "vendor.js"
  )
  .copy(
    "node_modules/font-awesome/fonts",
    "public/build/dashboard/fonts/font-awesome"
  )
  .copy(
    "node_modules/themify-icons-scss/fonts",
    "public/build/dashboard/fonts/themify-icons"
  )
  .copy(
    "node_modules/element-ui/lib/theme-chalk/fonts",
    "public/build/dashboard/fonts/element-ui"
  )
  .sourceMaps()
  .setPublicPath(distPath)
  .setResourceRoot("/");

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
