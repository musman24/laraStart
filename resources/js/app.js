
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// for the authorization
import Gate from './Gate'
Vue.prototype.$Gate = new Gate(window.user);

// for displaying dates & time in a readable format
import moment from 'moment'

// importing VueProgressBar & telling Vue that we're going to use it
import VueProgressBar from 'vue-progressbar'
const options = {
    color: '#bffaf3',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
}
Vue.use(VueProgressBar, options)

// importing sweetalert2 & requiring it
import Swal from 'sweetalert2/dist/sweetalert2.all.min.js'
window.Swal = Swal;
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
window.toast = Toast

// for form validation
import { Form, HasError, AlertError } from 'vform'
// form components
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

// for pagination
Vue.component('pagination', require('laravel-vue-pagination'));

// importing VueRouter & telling Vue that we're going to use it
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// array of routes
let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { path: '/developer', component: require('./components/Developer.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '*', component: require('./components/404.vue').default }
]

const router = new VueRouter({
    mode: 'history',
    routes, // short for router: router
    linkActiveClass: 'active'
})

// global filter to capitalize a word
Vue.filter('capitalize', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1);
});

// global filter to format date & time
Vue.filter('formateDateTime', function(dateTime){
    return moment(dateTime).format('Do MMMM YYYY');
})

// to create custom events
let Fire = new Vue();
window.Fire = Fire;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// The published components will be placed in your resources/js/components directory.
// Once the components have been published, you should register them in your resources/js/app.js file
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);
Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);
Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

// 404 error component
Vue.component(
    'not-found',
    require('./components/404.vue').default
);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router, // make sure to inject the router with router option to make the whole app router-aware
    data: {
        search: ''
    },
    methods: {
        searchIt: _.debounce(() => {
            Fire.$emit('search');
        }, 1000) // wait for 1sec and then fire the search event
    }
});
