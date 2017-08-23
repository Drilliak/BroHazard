const entry = require('./entries');

const path = require('path');

const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const ExtractTextPlugin = require("extract-text-webpack-plugin");

const dev = process.env.NODE_ENV === "dev";


const cssLoaders = [
    {
        loader: "css-loader", options: {
        importLoaders: 1,
        minimize: !dev
    }
    },
];

if (!dev) {
    cssLoaders.push({
        loader: 'postcss-loader',
        options: {
            plugins: (loader) => [
                require('autoprefixer')({
                    browsers: ['last 2 versions', 'ie > 8']
                }),
            ]
        }
    });
}

let config = {
    entry: entry,
    watch: dev,
    output: {
        path: path.resolve("./web/dist"),
        publicPath: '/dist/',
        filename: dev ? '[name].js' : '[name].[chunkhash].js'
    },
    resolve: {
        extensions: [".webpack.js", ".web.js", ".ts", ".tsx", ".js", ".css", ".scss"],
        alias: {
            '@css': path.resolve('./src/AppBundle/Resources/assets/css/')
        }
    },
    devtool: dev ? "cheap-module-eval-source-map" : false,
    devServer: {
        contentBase: path.resolve('./web')
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: ['babel-loader']
            },
            {
                test: /\.tsx?$/,
                loader: "ts-loader",
                options: {
                    transpileOnly: true
                }
            },
            {
                test: /\.css$/,
                use: ExtractTextPlugin.extract({
                    fallback: "style-loader",
                    use: cssLoaders
                })
            },
            {
                test: /\.scss$/,
                use: ExtractTextPlugin.extract({
                    fallback: "style-loader",
                    use: [...cssLoaders, 'sass-loader']
                })
            },
            {
                test: /\.(svg|woff2?|eot|ttf|otf)(\?.*)?$/,
                loader: 'file-loader'

            },
            {
                test: /\.(png|jpe?g|gif)$/,
                use: [
                    {
                        loader: 'url-loader',
                        options: {
                            limit: 8192,
                            name: '[name].[hash].[ext]'
                        }
                    },
                    {
                        loader: 'img-loader',
                        options: {
                            enabled: !dev
                        }
                    }
                ]
            }
        ]
    },
    plugins: [
        new CleanWebpackPlugin(['dist'], {
            root: path.resolve('./web'),
            verbose: true,
            dry: false

        }),
        new ExtractTextPlugin({
            filename: '[name].[contenthash].css',
            disable: dev
        })
    ]

};

if (!dev) {
    config.plugins.push(new UglifyJsPlugin({
        sourceMap: false,
        compress: {
            drop_console: true
        }
    }));
    config.plugins.push(new ManifestPlugin({
        basePath: "dist/"
    }));
}
module.exports = config;