var webpack = require('webpack');

module.exports = {
    entry: {
        app: './resources/assets/js/app',
        'user-list': './resources/assets/js/user-list'
    },
    output: {
        filename: '[name].js'
    },
    plugins: [
        new webpack.optimize.CommonsChunkPlugin({
            names: ['common-asset']
        })
    ]
};
