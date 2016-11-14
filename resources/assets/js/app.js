
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

/*
const app = new Vue({
    el: '#app'
});
*/

const userList = new Vue({
    el: '#userList',
    data: {
    },
    created: function() {
        var that = this;
        // テキストを全て列挙して、初期値をデータに記録する
        $(":text").each(function() {
            var $this = $(this);
            if ($this.attr("id").indexOf("user_") == -1) {
                return;
            }
            // データを設定
            that[$this.attr("id")] = $this.data("default");
        });
        // ロールのラジオボックスを列挙
        $(":radio").each(function() {
            var $this = $(this);
            // 要素を持っていない場合は、設定する
            if (!that.hasOwnProperty($this.attr("name"))) {
                that[$this.attr("name")] = "";
            }
            // データを持っていたら設定
            if ($this.data("default") !== undefined) {
                that[$this.attr("name")] = $this.data("default");
            }
        });
    }
});
