/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')
require('bootstrap/dist/js/bootstrap.bundle');
window.Vue = require('vue')

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component( 'chat-component', require('./components/ChatComponent.vue').default);
Vue.component( 'dep-chat-component', require('./components/DepChatComponent.vue').default);
Vue.component( 'chat-room-component', require('./components/ChatRoomComponent.vue').default);


// Vue.component( "example", {
//     template: '<div><h1>Example Component!</h1><ul v-if="features.length"><li v-for="feature in features">{{ feature.name }}</li></ul></div>',
//     data: function() {
//         return { features : [] }
//     }
// });

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
  el: '#app'
});
// const app2 = new Vue({
//   el: '#chat-room-app',
// });
 $('.chat_data').on('click',function(e){ 
         var chat_pan=$(this).prop('id');
         var dep_id=$("#"+chat_pan+"-val").val();  
         localStorage.setItem('cht_pn_id', dep_id);
         $("#chat_dep_id").val(dep_id); 
         $("#dep_show_chat").trigger('click');
         
        $("#loading_body").css('display','block');
        $("#chat_compon").css('display','block');
        setTimeout(() => {
          $("#loading_body").css('display','none');
         // $("#chat_compon").css('display','block');
        }, 2000);
      });
 

    $( document ).ready(function() {
        console.log('start checking for chat......'); 
       if(localStorage.getItem('cht_pn_id')!=null){
             var depp_chat_id=localStorage.getItem('cht_pn_id');
             $("#chat_dep_id").val(depp_chat_id); 
             $("#dep_show_chat").trigger('click');
         
         //   $("#loading_body").css('display','block');
            $("#chat_compon").css('display','none');
            setTimeout(() => { 
                $("#loading_body").css('display','none');
                $("#chat_compon").css('display','block');}, 1000);
       }else{

       }
  
     

    });