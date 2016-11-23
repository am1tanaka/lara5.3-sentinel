
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
Vue.component('modal', require('./components/Modal.vue'));

/*
const app = new Vue({
    el: '#app'
});
*/

if ($("#user-list").length > 0) {
    // ユーザー一覧を起動
    var UserList = Vue.extend({
        mixins: [require('./user-list')]
    });
    var userList = new UserList();
}
