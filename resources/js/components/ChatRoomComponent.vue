<template>
    <div class="card dc_chat_room" v-if="uniqueId">
        <div class="card-body py-2 px-3 border-bottom border-light ">
            <div class="d-flex align-items-start">
                <div>
                    <h5 class="mt-0 mb-0 font-15">
                        <a href="javascript:void(0)" class="text-reset" v-if="authUser.id==chatDetails.user_id_1">
                            {{chatDetails.user_name_2}}
                        </a>
                        <span v-if="authUser.id==chatDetails.user_id_2">{{chatDetails.user_name_1}}</span>
                    </h5>
                    <h6 class="mt-1 mb-0">{{ chatDetails.dep_name }}
                        <span class="dep_chat_id" v-if="chatDetails.depart_id">({{ chatDetails.depart_id }})</span>
                    </h6> 
                </div>
            </div>
        </div>

        <div class="card-body chat_area" id="cht_bdy">
            <ul class="conversation-list px-0">
                <li class="clearfix" v-for="(message, index) in messages" v-bind:key="message.id" :class="{ 'odd': message.author === authUser.email }">
                    <div class="chat-avatar">
                        <!-- <i>10:03</i> -->
                    </div>
                    <div class="conversation-text">
                        <div class="ctext-wrap" :class="index === messages.length-1?'last_msg_bd':index === 0?'first_msg_bd':'normal_msg_bd'" :id="index === messages.length-1?'last_msg_bd':index === 0?'first_msg_bd':index">
                           <template v-if="message.type === 'media'">
                            <a :href="message.mediaUrl" v-if=" message.media_type=='image/jpeg'  || message.media_type=='image/png'" target="_blank" >
                              <img class="img-thumbnail" :src="message.mediaUrl" :alt="message.filename" width="150px">
                            </a>
                            <a :href="message.mediaUrl" class="cht-media-doc" v-if=" message.media_type!='image/jpeg'  && message.media_type!='image/png'" target="_blank" >{{ message.filename }}</a>
                            <br><small>{{message.date}}</small>
                            </template>
                            <template v-else>
                              {{ message.body }}
                            <br><small>{{message.date}}</small>
                            </template>
                        </div>
                    </div>
                </li>
            </ul>
        </div>


         <div class="card-footer">
            <div class="row">
                <div class="col">
                  <input
                    type="text"
                    v-model="newMessage"
                    class="form-control"
                    placeholder="Type your message..."
                    @keyup.enter="sendMessage"
                />
                </div>
                <div class="col-sm-auto d-flex align-items-center"> 
                  <button class="chat-image-upload" title="Send Files" style="border: 0; cursor: pointer;">
                      <label for="file-input"  style="cursor: pointer;">
                        <img src="/chat_upload.png" style="width: 32px" />
                      </label>

                      <input id="file-input" type="file"   @change="sendMediaMessage" />
                  </button>                    
                    <button v-on:click="sendMessage" class="btn btn-success chat-send ml-1"><i class="fa fa-paper-plane mr-1"></i></button>
 
                </div>
            </div>
        </div>
       
    </div>
</template>

<script>
export default {
    name: "ChatComponent",
    props: {
        authUser: {
            type: Object,
            required: true
        },
        chatDetails: {
            type: Object,
            required: true
        }
        ,
        uniqueId: {
            type:  [String, Number, Boolean],
            value: [Number,String],
            required: true
        }

    }, 
    data() {
        return {
            messages: [],
            newMessage: "",
            channel: "",
            channel2: ""
        };
    }, 
    async created() {

           const token = await this.fetchToken();
           if(token){
            await this.initializeClient(token);
            await this.fetchMessages();

           }
       
    },
    methods: {
        async fetchToken() {

            const { data } = await axios.post("/api/token", {
                email: this.authUser.email                
            });
             
            return data.token;
        },
        async initializeClient(token) { 
            // console.log(this.chatDetails);
            // console.log(this.uniqueId);
            // console.log(this.authUser);
 
        console.log(Twilio);
            const client = await Twilio.Chat.Client.create(token);

            client.on("tokenAboutToExpire", async () => {
                const token = await this.fetchToken();

                client.updateToken(token);
            });
 
            this.channel = await client.getChannelByUniqueName(
                `${this.uniqueId}`
            );

            this.channel.on("messageAdded", message => { 
               // this.messages.push(message);  
               const msg_length=this.messages.length?this.messages.length:0; 
                this.pushToArray(message,msg_length);             
               //this.setMessageDate(); 
            }); 
 


        },
        async fetchMessages() { 
            $("#loading_body").css('display','block');
            $("#chat_compon").css('display','none');
            const messages = (await this.channel.getMessages()).items; 
             //console.log(this.messages);   
             
             var cnt=0;         
             for (const message of messages) {                
                    console.log(message);
                    this.pushToArray(message,cnt);
                    cnt++;
             }             
              //this.setMessageDate(); 
              $("#loading_body").css('display','none');
              $("#chat_compon").css('display','block');
               
             
                        
        },
        async pushToArray (message,cnt) {
          if (message.type === 'media') {
            const mediaUrl = await message.media.getContentUrl();
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

               setTimeout(() =>  this.setMessageDate(), 4000); 
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
             setTimeout(() => {
                console.log($('#cht_bdy').height(),'cht');
               $('#cht_bdy').animate({scrollTop: 5000});
                       //$('#cht_bdy').scrollTop($('#cht_bdy').height()+1200);
                }, 400);
 
        },
        sendMediaMessage({ target }) {
          const formData = new FormData();
          formData.append('file', target.files[0]);


          this.channel.sendMessage(formData);
          target.value = "";
            setTimeout(() => { 
               $('#cht_bdy').animate({scrollTop: 5000});
             }, 400);
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
        sendMessage() { 
          
            if(this.newMessage){
                this.channel.sendMessage(this.newMessage);
                this.newMessage = "";
            }
            setTimeout(() => { 
               $('#cht_bdy').animate({scrollTop: 5000});
             }, 400);
        },
        scroll_to(){
           // alert($("#last_msg_bd").offset().top);
            $('#chat_area').animate({
                    scrollTop: $("#last_msg_bd").offset().top
                }, 1000); 
        }
    }
};
</script>
<style type="text/css">
    
</style>
