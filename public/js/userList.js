webpackJsonp([1],{

/***/ 0:
/***/ function(module, exports, __webpack_require__) {

	__webpack_require__(1);
	
	Vue.component('modal', __webpack_require__(12));
	
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


/***/ },

/***/ 12:
/***/ function(module, exports, __webpack_require__) {

	var __vue_exports__, __vue_options__
	var __vue_styles__ = {}
	
	/* template */
	var __vue_template__ = __webpack_require__(13)
	__vue_options__ = __vue_exports__ = __vue_exports__ || {}
	if (
	  typeof __vue_exports__.default === "object" ||
	  typeof __vue_exports__.default === "function"
	) {
	if (Object.keys(__vue_exports__).some(function (key) { return key !== "default" && key !== "__esModule" })) {console.error("named exports are not supported in *.vue files.")}
	__vue_options__ = __vue_exports__ = __vue_exports__.default
	}
	if (typeof __vue_options__ === "function") {
	  __vue_options__ = __vue_options__.options
	}
	__vue_options__.__file = "/Users/yutanaka/git/lara5.3-sentinel/resources/assets/js/components/Modal.vue"
	__vue_options__.render = __vue_template__.render
	__vue_options__.staticRenderFns = __vue_template__.staticRenderFns
	
	/* hot reload */
	if (false) {(function () {
	  var hotAPI = require("vue-hot-reload-api")
	  hotAPI.install(require("vue"), false)
	  if (!hotAPI.compatible) return
	  module.hot.accept()
	  if (!module.hot.data) {
	    hotAPI.createRecord("data-v-4631772c", __vue_options__)
	  } else {
	    hotAPI.reload("data-v-4631772c", __vue_options__)
	  }
	})()}
	if (__vue_options__.functional) {console.error("[vue-loader] Modal.vue: functional components are not supported and should be defined in plain js files using render functions.")}
	
	module.exports = __vue_exports__


/***/ },

/***/ 13:
/***/ function(module, exports, __webpack_require__) {

	module.exports={render:function (){var _vm=this;
	  return _vm._m(0)
	},staticRenderFns: [function (){var _vm=this;
	  return _vm._h('div', {
	    staticClass: "modal fade",
	    attrs: {
	      "id": "modal",
	      "tabindex": "-1",
	      "role": "dialog"
	    }
	  }, [_vm._h('div', {
	    staticClass: "modal-dialog"
	  }, [_vm._h('div', {
	    staticClass: "modal-content"
	  }, [_vm._h('div', {
	    staticClass: "modal-header"
	  }, [_vm._h('button', {
	    staticClass: "close",
	    attrs: {
	      "type": "button",
	      "data-dismiss": "modal",
	      "aria-label": "Close"
	    }
	  }, [_vm._h('span', {
	    attrs: {
	      "aria-hidden": "true"
	    }
	  }, ["×"])]), " ", _vm._h('h4', {
	    staticClass: "modal-title",
	    attrs: {
	      "id": "modal-title"
	    }
	  })]), " ", _vm._h('div', {
	    staticClass: "modal-body",
	    attrs: {
	      "id": "modal-body"
	    }
	  }), " ", _vm._h('div', {
	    staticClass: "modal-footer"
	  }, [_vm._h('button', {
	    staticClass: "btn btn-primary",
	    attrs: {
	      "type": "submit",
	      "id": "modal-update"
	    }
	  }, [_vm._h('i', {
	    staticClass: "fa fa-btn fa-check"
	  }), "  はい\n          "]), " ", _vm._h('button', {
	    staticClass: "btn btn-default",
	    attrs: {
	      "type": "button",
	      "data-dismiss": "modal"
	    }
	  }, [_vm._h('i', {
	    staticClass: "fa fa-btn fa-close"
	  }), "いいえ"])])])])])
	}]}
	module.exports.render._withStripped = true
	if (false) {
	  module.hot.accept()
	  if (module.hot.data) {
	     require("vue-hot-reload-api").rerender("data-v-4631772c", module.exports)
	  }
	}

/***/ }

});
//# sourceMappingURL=userList.js.map