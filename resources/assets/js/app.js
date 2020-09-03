require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import store from './store'
import VueRouter from 'vue-router'

import MessengerComponent from './components/MessengerComponent.vue';
import ExampleComponent from './components/ExampleComponent.vue';


// window.eventBus = new Vue();
Vue.use(VueRouter);
Vue.use(BootstrapVue);

Vue.component('contact-form-component', require('./components/ContactFormComponent.vue'));
Vue.component('profile-form-component', require('./components/ProfileFormComponent.vue'));
Vue.component('contact-component', require('./components/ContactComponent.vue'));
Vue.component('contact-list-component', require('./components/ContactListComponent.vue'));
Vue.component('active-conversation-component', require('./components/ActiveConversationComponent.vue'));
Vue.component('message-conversation-component', require('./components/MessageConversationComponent.vue'));

// Vue.component('messenger-component', require('./components/MessengerComponent.vue'));

Vue.component('status-component', require('./components/StatusComponent.vue'));

const routes = [
    { path: '/chat', component: MessengerComponent },
    { path: '/chat/:conversationId', component: MessengerComponent },
    { path: '/example', component: ExampleComponent }
  ];

  const router = new VueRouter({
    routes, // short for `routes: routes`
    mode:'history'
  });


const app = new Vue({
    el: '#app',
    store,//en ecma6 se puede abreviar cuando el atributo y la variable son iguales store: store
    router,
    methods:{
        logout(){
            document.getElementById('logout-form').submit();
        }
    }
});
