<template>
    <div class="card" v-if="uniqueId">
        <div class="card-header">{{ otherUser.name }}-{{uniqueId}}</div>
        <div class="card-body">
            <div v-for="message in messages" v-bind:key="message.id">
             <div :class="{ 'text-right': message.author === authUser.email }"
                >  {{ message.body }}
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-10">
                  <input
                    type="text"
                    v-model="newMessage"
                    class="form-control"
                    placeholder="Type your message..."
                    @keyup.enter="sendMessage"
                />
                <!-- <div class="form-group col-md-4"> -->
                  <input type="file"  @change="sendMediaMessage">
                <!-- </div> -->
                </div>
                <div class="col-md-2"> 
                    <button v-on:click="sendMessage" class="btn btn-primary"><i class="fa fa-paper-plane mr-1"></i>Send</button>
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
        otherUser: {
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
    // props:['authUser','otherUser','uniqueId'],
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
        await this.initializeClient(token);
        await this.fetchMessages();
    },
    methods: {
        async fetchToken() {

            const { data } = await axios.post("/api/token", {
                email: this.authUser.email                
            });
             
            return data.token;
        },
        async initializeClient(token) { 
            const client = await Twilio.Chat.Client.create(token);

            client.on("tokenAboutToExpire", async () => {
                const token = await this.fetchToken();

                client.updateToken(token);
            });

              //  `${this.authUser.id}-${this.otherUser.id}`
            this.channel = await client.getChannelByUniqueName(
                `${this.uniqueId}`
            );

            this.channel.on("messageAdded", message => { 
                this.messages.push(message);
            }); 

        },
        async fetchMessages() { 
            this.messages = (await this.channel.getMessages()).items; 
             console.log(this.messages);
        },
        sendMessage() { 
            this.channel.sendMessage(this.newMessage);

            this.newMessage = "";
        },
        sendMediaMessage({ target }) {
          const formData = new FormData();
          formData.append('file', target.files[0]);


          this.channel.sendMessage(formData);
          target.value = "";
        },
    }
};
</script>
