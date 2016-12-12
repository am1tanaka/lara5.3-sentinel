require('./bootstrap');

Vue.component('modal', require('./components/Modal.vue'));

var UserList = new Vue({
    el: '#user-list',
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
                // 実行時の動作を指定する
                var $userlist = $('#user-list-form');
                this.showModal({
                    title: 'ユーザー情報を変更しますか？',
                    body: '<table class="table table-striped"><tr>'+changed.join('</tr><tr>')+'</tr></table>',
                    method: 'PUT',
                    action: '/users/'+userid
                });
            }
        },
        deleteUser: function(event, userid) {
            this.showModal({
                title: 'ユーザー削除',
                body: "ユーザー <b>["+this.defaultValues['user_'+userid+'_name']+"]</b> を削除しますか？",
                method: 'DELETE',
                action: '/users/'+userid
            });
        },
        showModal: function(params) {
            var $userlist = $('#user-list-form');

            // フォームデータを設定
            $('#_method').val(params.method);
            $userlist.attr('action', $userlist.data('url')+params.action);

            // モーダルの表示内容を設定
            $('#modal-title').text(params.title);
            $('#modal-body').html(params.body);
            // モーダルを表示
            $('#modal').modal('show');
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
