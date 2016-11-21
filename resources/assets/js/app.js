
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
            defaultValues: [],
            showModal: false
        },
        methods: {
            escapeHTML: function(html) {
                return $('<div>').text(html).html();
            },
            updateUser: function(event, userid) {
                // データの更新があるかをチェック
                var changed = [];
                var params = [
                    ['ユーザー名', 'user_'+userid+'_name'],
                    ['メールアドレス', 'user_'+userid+'_email'],
                    ['ロール', 'user_'+userid+'_role']
                ];

                for (var i=0 ; i<3; i++) {
                    if (this[params[i][1]] !== this.defaultValues[params[i][1]]) {
                        changed[i] = '<td>'+params[i][0]+'</td><td>'
                            +this.escapeHTML(this.defaultValues[params[i][1]])+'</td><td> -&gt;</td><td>'
                            +this.escapeHTML(this[params[i][1]])+'</td>';
                    }
                }

                if (changed.length == 0) {
                    alert("変更点はありません。");
                }
                else {
                    alert($('#user-update-form').attr('action'));

                    $('#modal-body').html('<table class="table table-striped"><tr>'+changed.join('</tr><tr>')+'</tr></table>');
                    // モーダルを表示
                    $('#modal').modal('show');
                }
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
