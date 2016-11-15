
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

if ($("#userList").length > 0) {
    const userList = new Vue({
        el: '#userList',
        data: {
            defaultValues: []
        },
        methods: {
            updateUser: function(event, userid) {
                alert(userid);
                alert($(event.target).attr("id"));
                // データの更新があるかをチェック
            }
        },
        created: function() {
            var that = this;
            // テキストを全て列挙して、初期値をデータに記録する
            $(":text").each(function() {
                var $this = $(this);
                if ($this.attr("id").indexOf("user_") == -1) {
                    return;
                }
                // 初期値をHTMLとデフォルト値として記憶
                that[$this.attr("id")] = $this.data("default");
                that.defaultValues[$this.attr("id")] = $this.data("default");
            });
            // ロールのラジオボックスを列挙
            $(":radio").each(function() {
                var $this = $(this);
                // 要素がない場合は追加する
                if (!that.hasOwnProperty($this.attr("name"))) {
                    that[$this.attr("name")] = "";
                    that.defaultValues[$this.attr("name")] = "";
                }
                // デフォルト値を持っていたら設定
                if ($this.data("default") !== undefined) {
                    that[$this.attr("name")] = $this.data("default");
                    that.defaultValues[$this.attr("name")] = $this.data("default");
                }
            });
        }
    });
}
