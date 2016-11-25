var webpack = require('webpack');

// http://qiita.com/m0a/items/34df129d6d8991ebbf86

module.exports = {
    entry: {
        app: './resources/assets/js/app',
        userList: './resources/assets/js/user-list'
    },
    output: {
        filename: '[name].js'
    },
    module: {
        loaders: [
            { test: /\.vue$/, loader: 'vue' },
        ]
    },
    plugins: [
        new webpack.optimize.CommonsChunkPlugin({
            names: ['common-asset']
        })
    ]
};
