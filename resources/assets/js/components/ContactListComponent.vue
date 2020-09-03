<template>

        <b-list-group>
            
            <contact-component 
                v-for="conversation in conversationsFiltered"
                :key="conversation.id"
                :conversation="conversation"
                :selected="isSelected(conversation)"
                @click.native="selectConversation(conversation)">

            </contact-component>

        </b-list-group>

</template>

<script>
    export default {
        methods:
        {
            isSelected(conversation){

                if(this.selectedConversation)
                {
                    return this.selectedConversation.id === conversation.id;
                }

                return false;
            },
            selectConversation(conversation){

                // this
                //     .$store
                //     .dispatch('getMessages', conversation)
                //     .then(()=>{
                //         this.$router.push(`/chat/${conversation.id}`);
                //     });


                this.$router.push(`/chat/${conversation.id}`, ()=>{
                    this.$store.dispatch('getMessages', conversation);
                });

            }
        },
        computed:{
            selectedConversation()
            {
                return this.$store.state.selectedConversation;
            },
            conversationsFiltered()
            {
                return this.$store.getters.conversationsFiltered;
            }
        }
    }
</script>
   