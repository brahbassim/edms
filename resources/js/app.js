require('./bootstrap');
import Vue from 'vue';
window.Vue = require('vue');
import VueProgressBar from 'vue-progressbar';
import Permissions from './mixins/Permissions';
import Helpers from './mixins/Helpers';
window.EventBus = window.EventBus || new Vue();




Vue.mixin(Permissions);
Vue.mixin(Helpers);

Vue.component('auth-login', require('./components/Auth/Login.vue').default);
Vue.component('user-manage', require('./components/User/Manage.vue').default);
Vue.component('user-role', require('./components/User/Role.vue').default);
Vue.component('profile-edit', require('./components/Profile/Edit.vue').default);
Vue.component('profile-partial', require('./components/Profile/Partial.vue').default);
Vue.component('dashboard', require('./components/Dashboard.vue').default);
Vue.component('document-manage', require('./components/Document/Manage.vue').default);
Vue.component('folder-manage', require('./components/Folder/Manage.vue').default);
Vue.component('pagination', require('./components/Pagination.vue').default);
Vue.use(VueProgressBar, {color: '#34495e', thickness: '6px', transition: {speed: '0.2s', opacity: '0.6s', termination: 300}, autoRevert: true, location: 'top', inverse: false})
const app = new Vue({
    el: '#wrapper',
});
