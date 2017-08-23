const PATH = {
    assets: './src/AppBundle/Resources/assets',
    js: './src/AppBundle/Resources/assets/js',
    css: './src/AppBundle/Resources/assets/css'
};

module.exports = {
    "base": PATH.js + '/base.js',
    "bootstrap": PATH.js + "/bootstrap.js",
    "Admin/dashboard-user": PATH.js + "/Admin/dashboard-user.js",
    "Post/post": PATH.js + "/Post/post.js",
    "Post/vote-bar": PATH.js + "/Post/vote-bar.js"
};