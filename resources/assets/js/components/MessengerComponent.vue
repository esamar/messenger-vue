<template>

    <b-container fluid style="height: calc( 100vh - 56px)">
        
        <b-row no-gutters class="h-100">

            <b-col cols ="4">
                <b-form class="my-3 mx-2">
                    
                    <b-form-input class="text-center"
                        type="text"
                        v-model="querySearch"
                        placeholder="Buscar contacto...">
                    </b-form-input>
                
                </b-form>
                <contact-list-component 
                    @conversationSelected="changeActiveConversation($event)"
                    :conversations="conversationFiltered">

                </contact-list-component>

            </b-col>

            <b-col cols="8">

                <active-conversation-component
                    v-if="selectedConversation"
                    :contact-id="selectedConversation.contact_id"
                    :contact-name="selectedConversation.contact_name"
                    :contact-image="selectedConversation.contact_image"
                    :my-image="myImageUrl"
                    :messages="messages"
                    @messageCreated="addMessage($event)">
                </active-conversation-component>

            </b-col>

        </b-row>

    </b-container>


</template>

<script>
    export default {
        props: {
            user: Object
        },
        data()
        {
            return{
                selectedConversation: null,
                messages: [],
                conversations: [],
                querySearch : ''
            };

        },
        mounted() {
            
            this.getConversations();

            Echo.private(`users.${this.user.id}`)
            .listen('MessageSend',(data) => {
                const message = data.message;
                message.written_by_me = false;
                this.addMessage(message);
            });

            Echo.join(`messenger`)
            .here((users) => {
                // console.log('online ' , users);
                users.forEach((user)=> this.changeStatus(user, true));
                
            })
            .joining((user) => {
                this.changeStatus(user , true);
                // console.log('Acabo de conectarme '+user.id);
                
            })
            .leaving((user) => {
                this.changeStatus(user , false);
                // console.log('Acabo de salir ' + user.id);
            });
            
        },
        methods:
        {
            changeActiveConversation(conversation)
            {
                this.selectedConversation = conversation;
                this.getMessages();
            },   
            getMessages()
            {
                axios.get(`/api/messages?contact_id=${this.selectedConversation.contact_id}`)
                .then((response)=> {
                    // console.log(response.data);
                    this.messages= response.data;
                    // console.log(this.messages);
                });
            } ,
            addMessage(message)
            {

                const conversation = this.conversations.find(function(conversation)
                {
                    return conversation.contact_id == message.from_id || conversation.contact_id == message.to_id;
                });

                const author = this.user.id === message.from_id ? 'Tú' : conversation.contact_name;

                conversation.last_message = `${author}: ${message.content}`;
                conversation.last_time = message.created_at;

                if (this.selectedConversation.contact_id == message.from_id || this.selectedConversation.contact_id == message.to_id)
                {
                    this.messages.push(message);
                }

            },
            getConversations()
            {
                axios.get('api/conversations')
                .then((response)=>{
                    this.conversations = response.data;
                    // console.log(response.data);
                })
            },
            changeStatus(user, status)
            {
                const index = this.conversations.findIndex((conversation)=>{
                    return conversation.contact_id == user.id;
                })
                // this.conversations[index].online = true; Agrega una propiedad pero No actualiza de forma reactiva
                if ( index >= 0 )
                this.$set( this.conversations[index] , 'online' , status );//agregar la propiedad y vue lo reconoce de forma reactiva

            }
        },
        computed: {
            conversationFiltered()
            {
                return this.conversations.filter(
                    (conversation)=> conversation.contact_name.toLowerCase().includes(this.querySearch.toLowerCase())
                );
            },
            myImageUrl()
            {
                return `/users/${this.user.image}`;
            }
        }
    }
</script>
