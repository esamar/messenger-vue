<template>

    <b-container fluid style="height: calc( 100vh - 56px)">
        
        <b-row no-gutters class="h-100">

            <b-col cols ="4">

                <contact-form-component />
                
                <contact-list-component />

            </b-col>

            <b-col cols="8">

                <active-conversation-component
                    v-if="selectedConversation"
                />

            </b-col>

        </b-row>

    </b-container>


</template>

<script>
    export default {
        props: {
            user: Object
        },
        mounted() {
            
            this.$store.commit('setUser', this.user);
            this.$store
                .dispatch('getConversations')
                .then(()=>{
                    const conversationId = this.$route.params.conversationId;
                    if(conversationId)
                    {
                        const conversation = this.$store.getters.getConversationById(conversationId);
                        this.$store.dispatch('getMessages', conversation);
                    }

                });
        
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
            
            addMessage(message)
            {
                this.$store.commit('addMessage', message);
            },
            changeStatus(user, status)
            {
                const index = this.$store.state.conversations.findIndex((conversation)=>{
                    return conversation.contact_id == user.id;
                });
           
                if ( index >= 0 )
                    this.$set( this.$store.state.conversations[index] , 'online' , status );//agregar la propiedad y vue lo reconoce de forma reactiva

            }
        },
        computed: {
            selectedConversation()
            {
                return this.$store.state.selectedConversation;
            }
        }
    }
</script>
