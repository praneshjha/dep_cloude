<template>
    <div>
    <input type="text" id="chat_dep_id" name="" style="display:none">
    <button id="dep_show_chat" @click="getChatDetails" style="display:none">show chat</button>
   
    
    <div class="card" v-if="show_chat">
        <div class="card-header">
            <span v-if="authUser.id==chatDetails.user_id_1">{{chatDetails.user_name_2}}</span>
            <span v-if="authUser.id==chatDetails.user_id_2 && chatDetails.user_name_2!=chatDetails.user_name_1">{{chatDetails.user_name_1}}</span>
            <small class="d-block">{{ chatDetails.dep_name }}</small>

               <div id="chat-minimized" v-on:click="minimize" title="Minimize">
                    <svg viewBox="0 0 24 24" fit="" height="100%" width="100%" preserveAspectRatio="xMidYMid meet" focusable="false"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M6 19h12v2H6z"></path></svg>
                </div>
                <div id="chat-maximized" v-on:click="maximize" style="display: none;" title="Maximize">
                    <svg viewBox="0 0 24 24" fit="" height="100%" width="100%" preserveAspectRatio="xMidYMid meet" focusable="false"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M3 3h18v2H3z"></path></svg>
                </div>
                <div id="chat-closed" v-on:click="chatClose" title="Close">
                    <svg viewBox="0 0 24 24" fit="" height="100%" width="100%" preserveAspectRatio="xMidYMid meet" focusable="false"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"></path></svg>
                </div>


             </div>

        <div class="card-body" id="cht_bdy"> 
            <div class="loader--spinner text-center" v-if="loader" >
               <div class="loading_chat exemple4 color4"><p>Loading</p></div> 
           </div>
            <div v-for="(message, index) in messages" v-bind:key="message.id"  :class="message.author === authUser.email?'text-right chat-sender':'text-left chat-receiver'"  >
                 <div class="chat_t_body"  :class="index === messages.length-1?'last_msg_bd':index === 0?'first_msg_bd':'normal_msg_bd'">  
                   <template v-if="message.type === 'media'">
                     <a :href="message.mediaUrl" v-if=" message.media_type=='image/jpeg'  || message.media_type=='image/png'" target="_blank" >
                      <img class="img-thumbnail" :src="message.mediaUrl" :alt="message.filename" width="150px"  >
                  </a>
                      <a :href="message.mediaUrl" v-if=" message.media_type!='image/jpeg'  && message.media_type!='image/png'" target="_blank" style="color:#fff">{{ message.filename }}</a>
                    <small>{{message.date}}</small>
                    </template>
                    <template v-else>
                      {{ message.body }}
                    <small>{{message.date}}</small>
                    </template>
                 </div>
            </div>
 
        </div>

        <div class="card-footer">
                <input
                    type="text"
                    v-model="newMessage"
                    class="form-control"
                    placeholder="Type your message..."
                    @keyup.enter="sendMessage"
                />

                  <!-- <input type="file"  @change="sendMediaMessage"> -->
                  <button class="chat-image-upload mr-1" title="Send Files">
                      <label for="file-input"  style="cursor: pointer;">
                        <img src="/chat_upload.png" width="25" />
                      </label>

                      <input id="file-input" type="file"  @change="sendMediaMessage" />
                  </button>
            <button v-on:click="sendMessage" class="btn btn-primary"><i class="fa fa-paper-plane mr-1"></i></button>
        </div>
    </div>
</div>  
</template> 

<script>
export default {
    name: "DepChatComponent",
    props: {
        authUser: {
            type: Object,
            required: true
        }, 

    },
    // props:['authUser','otherUser','uniqueId'],
    data() {
        return {
            dataMap : [],
            messages: [],
            newMessage: "",
            channel: "",
            channel2: "",
            otherUser:Object,
            uniqueId:String,
            show_chat:false,
            loader:true,
            chatDetails:Object
        };
    },
    async created() {
        // const token = await this.fetchToken();
        // await this.initializeClient(token);
        // await this.fetchMessages();
    },
    methods: {
        async fetchToken() {

            const { data } = await axios.post("/api/token", {
                email: this.authUser.email                
            });
             
            return data.token;
        },
        async initializeClient(token) { 
            console.log(token);
            const client = await Twilio.Chat.Client.create(token);

            client.on("tokenAboutToExpire", async () => {
                const token = await this.fetchToken();

                client.updateToken(token);
            });

              //  `${this.authUser.id}-${this.otherUser.id}`
            this.channel = await client.getChannelByUniqueName(
                `${this.uniqueId}`
            );
            console.log(this.channel);
            this.channel.on("messageAdded", message => { 
               // this.messages.push(message);  
                this.pushToArray(message);        
               // this.setMessageDate(); 
            }); 

        },
        async fetchMessages() {  
            this.messages = (await this.channel.getMessages()).items; 
             console.log(this.messages);
             for (const message of messages) {
                    this.pushToArray(message)
             }
        },
        sendMessage() { 
            if(this.newMessage){
                this.channel.sendMessage(this.newMessage);
                this.newMessage = "";
            }
            setTimeout(() => {
             //  $('#cht_bdy').scrollTop(0);
               $('#cht_bdy').animate({scrollTop: $('#cht_bdy').height() + $('#cht_bdy').height()+10});
        }, 400);

        },
        getChatDetails(){
         var dep_id=$("#chat_dep_id").val(); 
         let that=this;
              axios.get("/check_chat_channel/"+dep_id).then(function (response) {

             console.log(response);
             that.otherUser=response.data.user;
             that.uniqueId=response.data.unique_id;
             that.chatDetails=response.data.chatDetails;
             that.show_chat=true; 

              const token =  that.fetchToken2();  

              });
             
        },
        fetchToken2() {
         let that=this;
         
             axios.post("/api/token", {
                email: this.authUser.email                
            }).then(function (response) {
             console.log(response);//return response.data.token;
             that.initializeClient(response.data.token);
             setTimeout(() =>  that.fetchMessages_chat(), 4000);

           });
             
           
        },
        fetchMessages_chat() { 
         let that=this; 
         that.loader=true;
         that.messages=[];
             //$("#loading_body").css('display','block');
             this.channel.getMessages().then(function (response) { 
               if(localStorage.getItem('cht_mnz')!=null){
                that.minimize();
              $("#loading_body").css('display','none');
              $("#chat_compon").css('display','block');
               }else{
                 that.maximize();    
              $("#loading_body").css('display','none');
              $("#chat_compon").css('display','block');           
               }
              const messages=response.items; 
                           
             for (const message of messages) {
                    that.pushToArray(message)
             }
             // that.setMessageDate();

              
             that.loader=false;


             });
        },
        setMessageDate(){
            let i=0;
            if(this.messages && this.messages.length>0){
                for(let msg of this.messages){ 
                    this.messages[i]['date']=this.convertDate(msg.timestamp); 
                 i++;   
                }

                this.messages.sort(function(a,b){
                  return new Date(a.date) - new Date(b.date)
                });
            }
 
        },

        convertDate(str) {   
                var date_ob = new Date(str); 
                var year = date_ob.getFullYear();
                const monthNames = ["January", "February", "March", "Ap", "May", "June", "July", "August", "September", "October", "November", "December"  ];
                const month_names_short= ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                var month = month_names_short[date_ob.getMonth()];  
                var date = ("0" + date_ob.getDate()).slice(-2); 
                var hours = ("0" + date_ob.getHours()).slice(-2); 
                var minutes = ("0" + date_ob.getMinutes()).slice(-2); 
                var seconds = ("0" + date_ob.getSeconds()).slice(-2); 
                 
                return  month + " " + date + " " +  year + " " + hours + ":" + minutes;
 
 
        }, 
        minimize(){
          localStorage.setItem('cht_mnz',1); 
          $("#chat_compon .card").addClass("min");
          $("#chat-maximized").show();
          $('#chat-minimized').hide();
        },
        maximize(){
          localStorage.removeItem('cht_mnz');
         $("#chat_compon .card").removeClass("min");
          $("#chat-maximized").hide();
          $('#chat-minimized').show();

          console.log('maximize',$('#cht_bdy').height());
          setTimeout(() => {
               $('#cht_bdy').animate({scrollTop: $('#cht_bdy').height() + $('#cht_bdy').height()+10});
                }, 1400);  
        },
        chatClose(){
          this.show_chat=false;
          this.loader=false;
          localStorage.removeItem('cht_pn_id');
          localStorage.removeItem('cht_mnz');
        },
        sendMediaMessage({ target }) {
          const formData = new FormData();
          formData.append('file', target.files[0]);


          this.channel.sendMessage(formData);
          target.value = "";
        },
        async pushToArray (message) {
          if (message.type === 'media') {
            const mediaUrl = await message.media.getContentUrl()


            this.messages.push({
              type: message.type,
              author: message.author,
              timestamp: message.timestamp,
              filename: message.media.filename,
              mediaUrl,
              media_type:message.media.contentType,
              body: "" 
            })
          } else {
            this.messages.push({
              type: message.type,
              author: message.author,
              timestamp: message.timestamp,
              body: message.body,
            })
          }
          setTimeout(() =>  this.setMessageDate(), 2000);
          
        },


        onlyUnique() { 
 

// var all_messages = this.messages.map(x => "channel: " + x.channel + ",services: " + x.services + ",state: " + x.state +  ",attributes: " + x.attributes + ",author: " + x.author + ",body: " + x.body + ",dateUpdated: " + x.dateUpdated + ",index: " + x.index + ",lastUpdatedBy: " + x.lastUpdatedBy + ",media: " + x.media + ",memberSid: " + x.memberSid + ",sid: " + x.sid + ",timestamp: " + x.timestamp.toLocaleDateString() + ",type: " + x.type );

var all_messages = this.messages.map(x => " author: " + x.author + ",body: " + x.body + ",dateUpdated: " + x.dateUpdated + ",index: " + x.index +  ",media: " + x.media + ",memberSid: " + x.memberSid + ",sid: " + x.sid + ",timestamp: " + x.timestamp.toLocaleDateString() + ",type: " + x.type );

//console.log(all_messages);

 let uniq_dates  = Array.from(new Set( all_messages.map(obj => obj.timestamp)));
                let temp_itens = [];
                let date_filtered;

                uniq_dates.filter(data=>{
                        let key = data;
                        date_filtered = all_messages.filter((item,index)=>{
                            temp_itens = [];
                            if(key==item.timestamp){
                               return item;
                            }  
                        })
                      this.dataMap.push(date_filtered);
                    });
                console.log(this.dataMap);

            } 
    }
};
</script> 

<style type="text/css">
 
@keyframes clignote {
  0% { opacity: .2; }
  50%{ opacity: .8; }
  100% { opacity: .2; }
}

@-webkit-keyframes load {
  0% {-webkit-transform: rotate(0deg);transform: rotate(0deg);}
  100% {-webkit-transform: rotate(360deg);transform: rotate(360deg);}
}
@keyframes load {
  0% {-webkit-transform: rotate(0deg);transform: rotate(0deg);}
  100% {-webkit-transform: rotate(360deg);transform: rotate(360deg);}
}



.loading_chat{
  display: inline-block; /* just for the test/exemple */
  /*display: block;*/
  margin: 6em 2em;
  font-size: 1em;
  position: relative;
  border-top: 1.1em solid #286090;
  border-right: 1.1em solid #F5F5F5;
  border-bottom: 1.1em solid #286090;
  border-left: 1.1em solid #F5F5F5;
  -webkit-animation: load 3.6s infinite linear;
  animation: load 3.6s infinite linear;
    position: relative;
  border-radius: 50%;
  width: 8em;
  height: 8em; 
}

.loading_chat p{
  position: absolute;
    content: '';
    margin: auto;
    top: .1em;
    left: .1em;
    right: .1em;
    bottom: .1em;
    line-height: 10em;
    text-align: center;
    color: #286090;
  font-family: sans-serif;
  border: 0;
  margin: 0;
  padding: 0;
    -webkit-animation: load 3.6s infinite linear reverse, clignote 1.6s infinite linear;
  animation: load 3.6s infinite linear reverse, clignote 1.6s infinite linear;
}

.loading_chat:before{
    position: absolute;
    display: block;
    content: '';
    margin: auto;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
  font-size: .8em;
    text-align: center;
  border-top: 1.1em solid #407CAD;
  border-right: 1.1em solid #F5F5F5;
  border-bottom: 1.1em solid #407CAD;
  border-left: 1.1em solid #F5F5F5;
  -webkit-animation: load 1.6s infinite linear reverse;
  animation: load 1.6s infinite linear reverse;
  border-radius: 50%;
}

.loading_chat:after{
    position: absolute;
    display: block;
    content: '';
    top: 1.1em;
    left: 1.1em;
    right: 1.1em;
    bottom: 1.1em;
  font-size: .8em;
    text-align: center;
  border-top: 1.1em solid #72ABDB;
  border-right: 1.1em solid #F5F5F5;
  border-bottom: 1.1em solid #72ABDB;
  border-left: 1.1em solid #F5F5F5;
  border-radius: 50%;
}


/**************************/
/* Just for exemple */
/**************************/

.exemple1{
  -webkit-animation: load 1.6s infinite linear;
  animation: load 1.6s infinite linear;
}

.exemple1 p{
    -webkit-animation: load 1.6s infinite linear reverse, clignote 1.6s infinite linear;
  animation: load 1.6s infinite linear reverse, clignote 1.6s infinite linear;
}


.exemple2{
  -webkit-animation: load 5.6s infinite linear;
  animation: load 5.6s infinite linear;
}

.exemple2 p{
    -webkit-animation: load 5.6s infinite linear reverse, clignote 1.6s infinite linear;
  animation: load 5.6s infinite linear reverse, clignote 1.6s infinite linear;
}

.exemple2:after{
  -webkit-animation: load 3.6s infinite linear;
  animation: load 3.6s infinite linear;
}

.exemple3{
  -webkit-animation: load 5.6s infinite linear;
  animation: load 5.6s infinite linear;
}

.exemple3 p{
    -webkit-animation: load 5.6s infinite linear reverse, clignote 1.6s infinite linear;
  animation: load 5.6s infinite linear reverse, clignote 1.6s infinite linear;
}

.exemple4{
  -webkit-animation: load 4.6s infinite linear;
  animation: load 4.6s infinite linear;
}

.exemple4 p{
    -webkit-animation: load 5.6s infinite linear reverse, clignote 1.6s infinite linear;
  animation: load 4.6s infinite linear reverse, clignote 1.6s infinite linear;
}

.exemple4:before{
  -webkit-animation: load 3.6s infinite linear;
  animation: load 3.6s infinite linear;
}

.exemple4:after{
  -webkit-animation: load 1.6s infinite linear;
  animation: load 1.6s infinite linear;
}


.exemple5{
  -webkit-animation: load 2s infinite linear reverse;
  animation: load 2s infinite linear reverse;
}

.exemple5 p{
    -webkit-animation: load 2s infinite linear, clignote 1.6s infinite linear;
  animation: load 2s infinite linear, clignote 1.6s infinite linear;
}

.exemple5:before{
  -webkit-animation: load 3.6s infinite linear;
  animation: load 3.6s infinite linear;
}

.exemple5:after{
  -webkit-animation: load 1.6s infinite linear;
  animation: load 1.6s infinite linear;
}



.color1{ border-color: #E2B900 #F5F5F5;}
.color1 p{color: #E2B900;}
.color1:before{border-color: #E8CB4A #F5F5F5;}
.color1:after{border-color: #EDD782 #F5F5F5;}

.color2{ border-color: #C91010 #F5F5F5;}
.color2 p{color: #C91010;}
.color2:before{border-color: #E03C3C #F5F5F5;}
.color2:after{border-color: #EA8181 #F5F5F5;}

.color3{ border-color: #299B25 #F5F5F5;}
.color3 p{color: #299B25;}
.color3:before{border-color: #50B24A #F5F5F5;}
.color3:after{border-color: #7DCE77 #F5F5F5;}

.color4{ border-color: #299B25 #F5F5F5;}
.color4 p{color: #C91010;}
.color4:before{border-color: #286090 #F5F5F5;}
.color4:after{border-color: #E2B900 #F5F5F5;}

.color5{ border-color: #E2B900 #C91010 #299B25 #286090;}
.color5 p{color: #C91010;}
.color5:before{border-color: #F5F5F5 #D1D1D1;}
.color5:after{border-color: #E2B900 #C91010 #299B25 #286090;}


</style>