<template>
    <b-img rounded="circle" blank width="10" height="10" 
    :title="online ? 'Conectado' : 'Desconectado'"
    :blank-color="online ? 'green' : 'gray'" alt="Circle image" class="m-1"></b-img>
    
</template>

<script>
    export default {
        props: {
            online : Boolean
        },
        data()
        {
            return{

            };

        },
        mounted() {
         
        }
    }
</script>